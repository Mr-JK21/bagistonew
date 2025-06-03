<?php

namespace Webkul\Marketplace\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webkul\Core\Rules\PhoneNumber;

class UserFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $sellerId = seller()->user()->id;
        $isUpdate = $this->method() == 'PUT';

        return [
            'name'                => ['required'],
            'email'               => ['required', 'email', $isUpdate ? "unique:marketplace_sellers,email,{$this->id}" : 'unique:marketplace_sellers,email'],
            'phone'               => ['required', new PhoneNumber],
            'password'            => [$isUpdate ? 'nullable' : 'required', 'min:6', 'confirmed'],
            'marketplace_role_id' => [
                'required',
                Rule::exists('marketplace_roles', 'id')->where(fn ($q) => $q->where('marketplace_seller_id', $sellerId)),
            ],
            'is_suspended'        => ['boolean'],
        ];
    }
}
