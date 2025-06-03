{!! view_render_event('bagisto.seller.product.edit.form.videos.before', ['product' => $product ?? null]) !!}

<div class="relative rounded-xl border bg-white p-4">
    <!-- Panel Header -->
    <div class="mb-4 flex justify-between gap-5">
        <div class="flex flex-col gap-2">
            <p class="text-base font-semibold text-gray-800">
                @lang('marketplace::app.seller.products.edit.videos.title')
            </p>

            <p class="text-xs font-medium text-gray-500">
                @lang('marketplace::app.seller.products.edit.videos.info', ['size' => core()->getMaxUploadSize()])
            </p>
        </div>
    </div>

    <!-- Video Blade Component -->
    <x-marketplace::seller.media.videos
        name="videos[files]"
        :allow-multiple="true"
        :uploaded-videos="$product?->videos ?? null"
    />

    <x-admin::form.control-group.error control-name='videos.files[0]' />
</div>

{!! view_render_event('bagisto.seller.product.edit.form.videos.after', ['product' => $product ?? null]) !!}
