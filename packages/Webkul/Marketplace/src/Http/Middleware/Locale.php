<?php

namespace Webkul\Marketplace\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\Core\Repositories\LocaleRepository;

class Locale
{
    /**
     * Create a middleware instance.
     *
     * @return void
     */
    public function __construct(protected LocaleRepository $localeRepository) {}

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $seller = Auth::guard('seller')->user();

        if (
            $seller
            && $seller->locale
        ) {
            if ($this->localeRepository->findOneByField('code', $seller->locale)) {
                app()->setLocale($seller->locale);

                session()->put('seller_locale', $seller->locale);
            }
        } else {
            if ($localeCode = session()->get('seller_locale')) {
                app()->setLocale($localeCode);
            } else {
                app()->setLocale(core()->getDefaultLocaleCodeFromDefaultChannel());
            }
        }

        return $next($request);
    }
}
