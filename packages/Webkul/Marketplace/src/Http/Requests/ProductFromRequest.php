<?php

namespace Webkul\Marketplace\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\Decimal;

class ProductFromRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $maxVideoFileSize = core()->getConfigData('catalog.products.attribute.file_attribute_upload_size') ?: '2048';

        return [
            'condition'                => 'required',
            'description'              => 'required',
            'price'                    => new Decimal,
            'variants.*.price'         => new Decimal,
            'variants.*.inventories.*' => 'nullable|numeric|min:0',
            'inventories.*'            => 'nullable|numeric|min:0',
            'images.files.*'           => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'images.positions.*'       => ['nullable', 'integer'],
            'videos.files.*'           => ['nullable', 'mimetypes:application/octet-stream,video/mp4,video/webm,video/quicktime', "max:{$maxVideoFileSize}"],
            'videos.positions.*'       => ['nullable', 'integer'],
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'videos.files.*.max' => trans('marketplace::app.seller.products.edit.videos.error'),
        ];
    }
}
