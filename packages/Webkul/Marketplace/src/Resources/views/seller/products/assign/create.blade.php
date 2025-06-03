<x-marketplace::seller.layouts>
    @php
        $allowInventory = ['configurable', 'bundle', 'downloadable'];

        $allowPrice = ['configurable', 'bundle'];

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        $productImage = $baseProduct->images()->first();
    @endphp

    <!-- Title of the page -->
    <x-slot:title>
        @lang('marketplace::app.seller.products.assign.title')
    </x-slot:title>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="seller_product_assign" />
    @endSection

    <!-- Assign Product Form -->
    <x-marketplace::seller.form
        :action="route('seller.products.assign.store', request()->route()->id)"
        enctype="multipart/form-data"
    >
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <h1 class="text-2xl font-medium">
                @lang('marketplace::app.seller.products.assign.title')
            </h1>

            <div class="flex items-center gap-x-2.5">
                <!-- Save Button -->
                <button class="primary-button px-5 py-2.5">
                    @lang('marketplace::app.seller.products.assign.save-btn')
                </button>
            </div>
        </div>

        <!-- Full Pannel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <div class="rounded border p-5">
                    <x-marketplace::seller.form.control-group.control
                        type="hidden"
                        name="product_type"
                        value="{{$baseProduct->type}}"
                    />

                    <div class="flex gap-4 max-sm:flex-wrap">
                        <!-- Condition -->
                        <x-marketplace::seller.form.control-group class="mb-2.5 w-full">
                            <x-marketplace::seller.form.control-group.label class="required">
                                @lang('marketplace::app.seller.products.assign.condition')
                            </x-marketplace::seller.form.control-group.label>

                            <x-marketplace::seller.form.control-group.control
                                type="select"
                                name="condition"
                                id="condition"
                                :value="old('condition')"
                                rules="required"
                            >
                                @foreach (['new', 'old'] as $type)
                                    <option value="{{ $type }}">
                                        @lang('marketplace::app.seller.products.assign.'.$type)
                                    </option>
                                @endforeach
                            </x-marketplace::seller.form.control-group.control>

                            <x-marketplace::seller.form.control-group.error control-name="condition" />
                        </x-marketplace::seller.form.control-group>

                        @if (! in_array($baseProduct->type, $allowPrice))
                        <!-- Price -->
                        <x-marketplace::seller.form.control-group class="mb-2.5 w-full">
                            <x-marketplace::seller.form.control-group.label class="required">
                                @lang('marketplace::app.seller.products.assign.price')
                            </x-marketplace::seller.form.control-group.label>

                            <x-marketplace::seller.form.control-group.control
                                type="price"
                                name="price"
                                :value="old('price')"
                                rules="required|decimal|min_value:0.01"
                                :label="trans('marketplace::app.seller.products.assign.price')"
                                :placeholder="trans('marketplace::app.seller.products.assign.price')"
                            />

                            <x-marketplace::seller.form.control-group.error control-name="price" />
                        </x-marketplace::seller.form.control-group>
                        @endif
                    </div>

                    @if (! in_array($baseProduct->type, $allowInventory)
                        && $baseProduct->type != 'downloadable'
                    )
                        <p class="mb-2.5 font-semibold text-gray-800">
                            @lang('marketplace::app.seller.products.assign.quantities')
                        </p>

                        @foreach ($inventorySources as $inventorySource)
                        <x-marketplace::seller.form.control-group class="mb-2.5">
                            <x-marketplace::seller.form.control-group.label>
                                {{$inventorySource->name}}
                            </x-marketplace::seller.form.control-group.label>

                            <x-marketplace::seller.form.control-group.control
                                type="text"
                                name="inventories[{{ $inventorySource->id }}]"
                                :value="old('inventories[{{ $inventorySource->id }}]')"
                                rules="numeric|min:0"
                                :placeholder="$inventorySource->name"
                            />

                            <x-marketplace::seller.form.control-group.error control-name="inventories[{{ $inventorySource->id }}]" />
                        </x-marketplace::seller.form.control-group>
                        @endforeach
                    @endif

                    <!-- Description -->
                    <x-marketplace::seller.form.control-group class="mb-2.5">
                        <x-marketplace::seller.form.control-group.label class="required">
                            @lang('marketplace::app.seller.products.assign.description')
                        </x-marketplace::seller.form.control-group.label>

                        <x-marketplace::seller.form.control-group.control
                            type="textarea"
                            name="description"
                            :value="old('description')"
                            id="content"
                            rules="required"
                            :label="trans('marketplace::app.seller.products.assign.description')"
                            :placeholder="trans('marketplace::app.seller.products.assign.description')"
                            :rows="5"
                        />

                        <x-marketplace::seller.form.control-group.error control-name="description" />
                    </x-marketplace::seller.form.control-group>

                    @include('marketplace::seller.products.edit.images', ['product' => []])

                    @include('marketplace::seller.products.edit.videos', ['product' => []])

                    @includeWhen($baseProduct->type == 'configurable', 'marketplace::seller.products.assign.types.configurable')

                    @includeWhen($baseProduct->type == 'downloadable', 'marketplace::seller.products.assign.types.downloadable')
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">
                <div class="rounded border p-5">
                    <p class="pb-4 text-base font-semibold leading-5 text-gray-800">
                        @lang('marketplace::app.seller.products.assign.product-details.title')
                    </p>
                
                    <!-- Product Information -->
                    <div class="flex gap-2">
                        <div class="w-full h-24 max-w-24 max-h-24 relative rounded-lg overflow-hidden @if (! $productImage) border border-dashed border-gray-300 @endif">
                            @if (! $productImage)
                                <img
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg', 'marketplace') }}"
                                    alt="Product placeholder"
                                >

                                <p class="absolute bottom-1 w-full text-center text-[8px] font-semibold text-gray-500">
                                    @lang('marketplace::app.seller.products.assign.product-details.image-placeholder')
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
                
                            <p class="text-sm font-normal text-gray-800">
                                {{ core()->formatBasePrice($baseProduct->getTypeInstance()->getMinimalPrice()) }}
                            </p>
                
                            <div
                                @class([
                                    'py-1.5 px-2 flex items-center justify-center rounded',
                                    'bg-green-100' => $baseProduct->status == 1,
                                    'bg-gray-200'  => $baseProduct->status != 1,
                                ])
                            >
                                <span
                                    @class([
                                        'text-sm font-medium leading-4',
                                        'text-green-600' => $baseProduct->status == 1,
                                        'text-gray-600'  => $baseProduct->status != 1,
                                    ])
                                >
                                    @if ($baseProduct->status == 1)
                                        @lang('marketplace::app.seller.products.assign.product-details.active')
                                    @else
                                        @lang('marketplace::app.seller.products.assign.product-details.disable')
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </x-marketplace::seller.form>
</x-marketplace::seller.layouts>
