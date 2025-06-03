<?php

namespace Webkul\Marketplace\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Route;

class Seller
{
    /**
     * Restricted routes.
     */
    protected const RESTRICTED_ROUTES = [
        'seller.profile.index',
        'seller.profile.update',
        'seller.settings.roles.index',
        'seller.settings.roles.create',
        'seller.settings.roles.store',
        'seller.settings.roles.edit',
        'seller.settings.roles.update',
        'seller.settings.roles.delete',
        'seller.settings.users.index',
        'seller.settings.users.store',
        'seller.settings.users.edit',
        'seller.settings.users.update',
        'seller.settings.users.delete',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $guard = 'seller')
    {
        return app(Pipeline::class)
            ->send($request)
            ->through([
                fn ($request, $next) => $this->checkAuthentication($request, $next, $guard),
                fn ($request, $next) => $this->checkApproval($request, $next, $guard),
                fn ($request, $next) => $this->checkPermissions($request, $next, $guard),
                fn ($request, $next) => $this->checkRestrictedRoutes($request, $next, $guard),
            ])
            ->then(fn () => $next($request));
    }

    /**
     * Check authentication.
     */
    protected function checkAuthentication($request, Closure $next, $guard)
    {
        if (! auth()->guard($guard)->check()) {
            return redirect()->route('seller.session.index');
        }

        return $next($request);
    }

    /**
     * Check approval.
     */
    protected function checkApproval($request, Closure $next, $guard)
    {
        $user = auth()->guard($guard)->user();

        if (! (bool) $user->is_approved) {
            auth()->guard($guard)->logout();

            return to_route('seller.session.index')
                ->with('warning', trans('marketplace::app.seller.login.not-approved'));
        }

        if ((bool) $user->is_suspended == 1) {
            auth()->guard($guard)->logout();

            return to_route('seller.session.index')
                ->with('error', trans('marketplace::app.seller.login.suspended-message'));
        }

        return $next($request);
    }

    /**
     * Check permissions.
     */
    protected function checkPermissions($request, Closure $next, $guard)
    {
        if ($this->isPermissionsEmpty($guard)) {
            abort(401, 'This action is unauthorized.');
        }

        return $next($request);
    }

    /**
     * Check restricted routes.
     */
    protected function checkRestrictedRoutes($request, Closure $next, $guard)
    {
        $user = auth()->guard($guard)->user();

        if ($this->restrictedRoutes($user)) {
            abort(401, 'This action is unauthorized.');
        }

        return $next($request);
    }

    /**
     * Check for seller, if they have empty permissions or not except seller.
     */
    public function isPermissionsEmpty($guard): bool
    {
        $user = auth()->guard($guard)->user();
        $role = $user->role;

        if (
            is_null($user->parent_id)
            || (
                $role
                && $role->permission_type === 'all'
            )
        ) {
            return false;
        }

        if (
            $role->permission_type !== 'all'
            && empty($role->permissions)
        ) {
            return true;
        }

        $roles = marketplace_acl()->getRoles();

        if (isset($roles[Route::currentRouteName()])) {
            seller()->allow($roles[Route::currentRouteName()]);
        }

        return false;
    }

    /**
     * Check for restricted routes.
     */
    public function restrictedRoutes($user): bool
    {
        return in_array(Route::currentRouteName(), self::RESTRICTED_ROUTES) && $user->parent_id;
    }
}
