<?php

namespace Webkul\Marketplace\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\AadharNumber;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PanNumber;
use Webkul\Core\Rules\Slug;

class SellerFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unique = 'unique:marketplace_sellers';
        $sellerId = $this->route('id');

        $rules = [
            'name'                   => ['required'],
            'email'                  => ['required', "$unique,email,".($sellerId ?? ''), 'email'],
            'url'                    => ['required', "$unique,url,".($sellerId ?? ''), 'lowercase', new Slug],
            'shop_title'             => ['required'],
            'banner.*'               => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'logo.*'                 => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'aadhar_card_pdf'        => ['nullable', 'mimes:pdf'],
            'meta_title'             => ['nullable'],
            'meta_description'       => ['nullable'],
            'meta_keywords'          => ['nullable'],
            'address'                => ['required', 'array', 'min:1'],
            'address.0'              => ['required'],
            'phone'                  => ['required', "$unique,phone,".($sellerId ?? ''), new PhoneNumber],
            'state'                  => ['required'],
            'city'                   => ['required'],
            'country'                => ['required'],
            'postcode'               => ['required'],
            'return_policy'          => ['nullable'],
            'shipping_policy'        => ['nullable'],
            'privacy_policy'         => ['nullable'],
            'min_order_amount'       => ['nullable', 'numeric', 'min:0.01'],
            'google_analytics_id'    => ['nullable'],
            'twitter'                => ['nullable'],
            'facebook'               => ['nullable'],
            'linkedin'               => ['nullable'],
            'pinterest'              => ['nullable'],
            'aadhar'                 => ['nullable', "$unique,aadhar,".($sellerId ?? ''), new AadharNumber],
            'pan'                    => ['nullable', "$unique,pan,".($sellerId ?? ''), new PanNumber],
        ];

        if ($this->isMethod('put')) {
            $rules = array_merge($rules, [
                'description'           => ['required'],
                'allowed_product_types' => ['array'],
                'commission_enable'     => ['boolean'],
                'is_suspended'          => ['boolean'],
                'commission_percentage' => ['required_with:commission_enable'],
            ]);
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'pan.regex' => 'Invalid PAN format (Example: ABCDE1234F).',
            'aadhar.digits' => 'Aadhar must be exactly 12 digits.',
        ];
    }
    /**
     * Attributes.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'address.0' => 'address',
            'aadhar' => 'Aadhar number',
            'pan' => 'PAN number',
        ];
    }
}
