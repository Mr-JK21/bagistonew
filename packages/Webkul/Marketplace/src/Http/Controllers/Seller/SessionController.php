<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;

class SessionController extends Controller
{
    /**
     * Display the resource.
     *
     * @return RedirectResponse|View
     */
    public function show()
    {
        return auth()->guard('seller')->check()
            ? redirect()->route('seller.dashboard.index')
            : view('marketplace::seller.sign-in');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (! auth()->guard('seller')->attempt(request()->only(['email', 'password']))) {
            session()->flash('error', trans('marketplace::app.seller.login.invalid-credentials'));

            return redirect()->back();
        }

        if (! auth()->guard('seller')->user()->is_approved) {
            session()->flash('info', trans('marketplace::app.seller.login.not-approved'));

            auth()->guard('seller')->logout();

            return redirect()->back();
        }

        $seller = auth()->guard('seller')->user();

        if ($seller->parent_id) {
            $permissions = $seller->role?->permissions ?? [];

            $firstPermission = collect($permissions)->first();

            if ($firstPermission) {
                $roles = marketplace_acl()->getRoles();

                $routeKey = $roles->search($firstPermission);

                if ($routeKey) {
                    return redirect()->route($routeKey);
                }
            }
        }

        return redirect()->route('seller.dashboard.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
        Event::dispatch('marketplace.seller.account.logout.before');

        auth()->guard('seller')->logout();

        Event::dispatch('marketplace.seller.account.logout.after');

        return redirect()->route('seller.session.index');
    }
}
