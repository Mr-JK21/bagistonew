<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Symfony\Component\Mime\Exception\RfcComplianceException;
use Webkul\Shop\Http\Requests\Customer\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('marketplace::seller.forgot-password');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(ForgotPasswordRequest $request)
    {
        $request->validated();

        try {
            $response = $this->broker()->sendResetLink($request->only(['email']));

            if ($response == Password::RESET_LINK_SENT) {
                session()->flash('success', trans('marketplace::app.seller.forgot-password.reset-link-sent'));

                return back();
            }

            return back()
                ->withInput($request->only(['email']))
                ->withErrors([
                    'email' => trans('marketplace::app.seller.forgot-password.email-not-exist'),
                ]);
        } catch (RfcComplianceException $e) {
            session()->flash('success', trans('marketplace::app.seller.forgot-password.reset-link-sent'));

            return redirect()->back();
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    public function broker()
    {
        return Password::broker('seller');
    }
}
