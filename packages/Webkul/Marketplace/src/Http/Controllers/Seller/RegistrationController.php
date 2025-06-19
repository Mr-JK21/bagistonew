<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Webkul\Core\Rules\Slug;
use Webkul\Marketplace\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SellerRepository $sellerRepository)
    {
    }

    /**
     * Opens up the user's sign up form.
     *
     * @return View
     */
    public function index()
    {
        return view('marketplace::seller.sign-up');
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @return Response
     */
    public function store()
    {
        // Log the incoming request data for debugging
        Log::info('Seller Register Request', [request()->toArray()]);

        // Validate the form inputs
        $data = request()->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:marketplace_sellers,email'],
            'url' => ['required', 'unique:marketplace_sellers,url', 'lowercase', new Slug],
            'password' => ['required', 'confirmed', 'min:6'],

            // 17th June 2025
            'phone' => ['required'],
            'aadhar' => ['required'],
            'pan' => ['required'], // standard PAN format
            'gst_number' => ['nullable'], // standard GST format
            // __
        ]);

        $this->sellerRepository->create($data);

        return to_route('seller.session.index')
            ->with('success', trans('marketplace::app.seller.signup.success'));
    }

}
