<x-admin::layouts>
    @php
        $allowInventory = ['configurable', 'bundle', 'downloadable', 'booking'];

        $allowPrice = ['configurable', 'bundle'];

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        $sellerId = request()->route()->seller_id;

        $productId = request()->route()->product_id;

        $productImage = $baseProduct->images->first();
    @endphp

    <!-- Title of the page -->
    <x-slot:title>
        @lang('marketplace::app.admin.sellers.assign-product.title')
    </x-slot:title>

    <!-- Assign Product Form -->
    <x-admin::form
        :action="route('admin.marketplace.sellers.products.save_assign', [
            'seller_id'  => $sellerId,
            'product_id' => $productId,            
        ])"
        enctype="multipart/form-data"
    >
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="py-2.5 text-xl font-bold text-gray-800 dark:text-white">
                @lang('marketplace::app.admin.sellers.assign-product.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Cancel Button -->
                <a
                    href="{{ route('admin.marketplace.sellers.products.search', $seller->id) }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('marketplace::app.admin.sellers.assign-product.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('marketplace::app.admin.sellers.assign-product.save-btn')
                </button>
            </div>
        </div>

        <!-- Full Pannel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="product_type"
                        value="{{$baseProduct->type}}"
                    />

                    <div class="flex gap-4 max-sm:flex-wrap">
                        <!-- Condition -->
                        <x-admin::form.control-group class="mb-2.5 w-full">
                            <x-admin::form.control-group.label class="required">
                                @lang('marketplace::app.admin.sellers.assign-product.condition')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="condition"
                                id="condition"
                                :value="old('condition')"
                                rules="required"
                            >
                                <option value="">
                                    @lang('marketplace::app.admin.sellers.assign-product.select-condition')
                                </option>

                                @foreach (['new', 'old'] as $type)
                                    <option value="{{ $type }}">
                                        @lang('marketplace::app.admin.sellers.assign-product.'.$type)
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="condition" />
                        </x-admin::form.control-group>

                        @if (! in_array($baseProduct->type, $allowPrice))
                            <!-- Price -->
                            <x-admin::form.control-group class="mb-2.5 w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('marketplace::app.admin.sellers.assign-product.price')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="price"
                                    name="price"
                                    :value="old('price')"
                                    rules="required|decimal|min_value:0.01"
                                    :label="trans('marketplace::app.admin.sellers.assign-product.price')"
                                    :placeholder="trans('marketplace::app.admin.sellers.assign-product.price')"
                                />

                                <x-admin::form.control-group.error control-name="price" />
                            </x-admin::form.control-group>
                        @endif
                    </div>

                    @if (! in_array($baseProduct->type, $allowInventory)
                        && $baseProduct->type != 'downloadable'
                    )
                        <p class="mb-2.5 font-semibold text-gray-800 dark:text-white">
                            @lang('marketplace::app.admin.sellers.assign-product.quantities')
                        </p>

                        @foreach ($inventorySources as $inventorySource)
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{$inventorySource->name}}
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="inventories[{{ $inventorySource->id }}]"
                                    :value="old('inventories[{{ $inventorySource->id }}]')"
                                    rules="numeric|min:0"
                                    :placeholder="$inventorySource->name"
                                />
                                <x-admin::form.control-group.error control-name="inventories[{{ $inventorySource->id }}]" />
                            </x-admin::form.control-group>
                        @endforeach
                    @endif

                    <!-- Description -->
                    <x-admin::form.control-group class="mb-2.5">
                        <x-admin::form.control-group.label class="required">
                            @lang('marketplace::app.admin.sellers.assign-product.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            :value="old('description')"
                            id="content"
                            rules="required"
                            :label="trans('marketplace::app.admin.sellers.assign-product.description')"
                            :placeholder="trans('marketplace::app.admin.sellers.assign-product.description')"
                        />
                        <x-admin::form.control-group.error control-name="description" />
                    </x-admin::form.control-group>

                    <div class="mb-4 flex justify-between gap-5">
                        <div class="flex flex-col gap-2">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('marketplace::app.admin.sellers.assign-product.images.title')
                            </p>
                
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                                @lang('marketplace::app.admin.sellers.assign-product.images.info')
                            </p>
                        </div>
                    </div>
                
                    <!-- Image Blade Component -->
                    <x-admin::media.images
                        name="images[files]"
                        allow-multiple="true"
                        show-placeholders="true"
                    />

                    <div class="flex justify-between gap-5 py-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('marketplace::app.admin.sellers.assign-product.videos.title')
                            </p>
                
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                                @lang('marketplace::app.admin.sellers.assign-product.videos.info', ['size' => core()->getMaxUploadSize()])
                            </p>
                        </div>
                    </div>

                    <!-- Video Blade Component -->
                    <x-admin::media.videos
                        name="videos[files]"
                        allow-multiple="true"
                        show-placeholders="true"
                    />

                    <x-admin::form.control-group.error control-name='videos.files[0]' />

                    @includeWhen($baseProduct->type == 'configurable', 'marketplace::admin.sellers.products.assign.configurable')

                    @includeWhen($baseProduct->type == 'downloadable', 'marketplace::admin.sellers.products.assign.downloadable', ['product' => $baseProduct])
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">
                <div class="box-shadow rounded bg-white py-4 dark:bg-gray-900">
                    <p class="px-4 pb-4 text-base font-semibold leading-5 text-gray-800 dark:text-white">
                        @lang('marketplace::app.admin.sellers.assign-product.product-details.title')
                    </p>
                
                    <!-- Product Information -->
                    <div class="flex gap-2 border-b px-4 pb-4">
                        <div class="w-full h-24 max-w-24 max-h-24 relative rounded-lg overflow-hidden @if (! $productImage) border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert @endif">
                            @if (! $productImage)
                                <img
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                                    alt="Product placeholder"
                                >

                                <p class="absolute bottom-1 w-full text-center text-[8px] font-semibold text-gray-500">
                                    @lang('marketplace::app.admin.sellers.assign-product.product-details.image-placeholder')
                                </p>
                            @else
                                <img
                                    src="{{ $productImage->url }}"
                                    alt="Product image"
                                    class="object-cover"
                                >
                            @endif
                        </div>
                
                        <div class="flex flex-col items-start justify-between gap-y-2">
                            @if (
                                $baseProduct->status
                                && $baseProduct->visible_individually
                                && $baseProduct->url_key
                            )
                                <a
                                    href="{{ route('shop.product_or_category.index', $baseProduct->url_key) }}"
                                    class="text-sm font-medium leading-4 text-blue-600"
                                    target="_blank"
                                >
                                    {{ $baseProduct->name }}
                                </a>
                            @else
                                <p class="text-sm font-medium leading-4 text-blue-600">
                                    {{ $baseProduct->name }}
                                </p>
                            @endif
                
                            <p class="text-sm font-normal text-gray-800 dark:text-gray-200">
                                {{ core()->formatBasePrice($baseProduct->getTypeInstance()->getMinimalPrice()) }}
                            </p>
                
                            <div
                                @class([
                                    'py-1.5 px-2 flex items-center justify-center rounded',
                                    'bg-green-100' => $baseProduct->status == 1,
                                    'bg-gray-200 dark:bg-gray-700' => $baseProduct->status != 1,
                                ])
                            >
                                <span
                                    @class([
                                        'text-sm font-medium leading-4',
                                        'text-green-600' => $baseProduct->status == 1,
                                        'text-gray-600 dark:text-gray-300' => $baseProduct->status != 1,
                                    ])
                                >
                                    @if ($baseProduct->status == 1)
                                        @lang('marketplace::app.admin.sellers.assign-product.product-details.active')
                                    @else
                                        @lang('marketplace::app.admin.sellers.assign-product.product-details.disable')
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                
                    <!-- Seller Information -->
                    <div class="flex flex-col">
                        <p class="p-4 text-base font-semibold leading-5 text-gray-800 dark:text-white">
                            @lang('marketplace::app.admin.sellers.assign-product.seller-details.title')
                        </p>
                
                        <div class="flex flex-col gap-y-2.5">
                            <div class="flex gap-2.5 px-4">
                                <div class="relative h-11 max-h-11 w-full max-w-11 overflow-hidden rounded-lg bg-gray-100">
                                    @if ($seller->logo) 
                                        <img
                                            src="{{ $seller->logo_url }}"
                                            alt="seller logo"
                                            class="object-cover"
                                        >
                                    @else
                                        <img
                                            src="{{ bagisto_asset('images/default-logo.webp', 'marketplace') }}"
                                            alt="seller logo"
                                        >
                                    @endif
                                </div>
                    
                                <div class="flex flex-col items-start justify-between">
                                    @if (
                                        $seller->is_approved
                                        && $seller->shop_title
                                        && $seller->url
                                    )
                                        <a
                                            href="{{ route('shop.marketplace.sellers.show', $seller->url) }}"
                                            class="text-sm font-medium leading-4 text-blue-600"
                                            target="_blank"
                                        >
                                            {{ $seller->shop_title }}
                                        </a>
                                    @else
                                        <p class="text-sm font-medium leading-4 text-blue-600">
                                            {{ $seller->shop_title }}
                                        </p>
                                    @endif
                    
                                    <p class="text-sm font-normal text-gray-800 dark:text-gray-200">
                                        {{ $seller->name }}
                                    </p>
                                </div>
                            </div>
                    
                            <div class="mt-1 flex flex-col gap-y-2.5 px-4">
                                <!-- Additional Info Section -->
                                <p class="text-sm font-medium leading-6 text-gray-700 dark:text-white">
                                    @lang('marketplace::app.admin.sellers.assign-product.seller-details.additional-info')
                                </p>
                                
                                <!-- Number of Products -->
                                <div class="grid grid-cols-2">
                                    <p class="text-sm font-normal leading-6 text-gray-800 dark:text-white">
                                        @lang('marketplace::app.admin.sellers.assign-product.seller-details.no-of-products')
                                    </p>

                                    <p class="text-sm font-semibold leading-6 text-gray-800 dark:text-white">
                                        {{ $totalProducts }}
                                    </p>
                                </div>
                                
                                <!-- Status Section -->
                                <div class="grid grid-cols-2">
                                    <p class="text-sm font-normal leading-6 text-gray-800 dark:text-white">
                                        @lang('marketplace::app.admin.sellers.assign-product.seller-details.status')
                                    </p>
    
                                    <div class="flex items-center justify-start gap-1">
                                        <div
                                            @class([
                                                'flex items-center justify-center rounded py-1.5 px-2 place-self-start',
                                                'bg-green-100' => $seller->is_approved == 1,
                                                'bg-gray-200 dark:bg-gray-700' => $seller->is_approved != 1,
                                            ])
                                        >
                                            <span
                                                @class([
                                                    'text-sm font-medium leading-4',
                                                    'text-green-600' => $seller->is_approved == 1,
                                                    'text-gray-600 dark:text-gray-300' => $seller->is_approved != 1,
                                                ])
                                            >
                                                @if ($seller->is_approved == 1)
                                                    @lang('marketplace::app.admin.sellers.assign-product.seller-details.approved')
                                                @else
                                                    @lang('marketplace::app.admin.sellers.assign-product.seller-details.disapproved')
                                                @endif
                                            </span>
                                        </div>

                                        @if ($seller->is_suspended)
                                            <span class="flex items-center justify-center rounded py-1.5 px-2 bg-pink-600">
                                                <span class="text-sm font-medium leading-4 text-white">
                                                    @lang('marketplace::app.admin.sellers.assign-product.seller-details.suspended')
                                                </span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
