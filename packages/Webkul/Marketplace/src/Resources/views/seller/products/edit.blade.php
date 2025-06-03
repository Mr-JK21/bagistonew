<x-marketplace::seller.layouts>
    <x-slot:title>
        @lang('marketplace::app.seller.products.edit.title')
    </x-slot:title>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="seller_product_edit" />
    @endSection

    {!! view_render_event('bagisto.seller.products.edit.before', ['product', $product]) !!}

    @php
        $channels = core()->getAllChannels();

        $currentChannel = core()->getRequestedChannel();

        $currentLocale = core()->getRequestedLocale();
    @endphp

    <x-marketplace::seller.form
        action="{{ route('seller.products.update', $product->id) }}"
        enctype="multipart/form-data"
    >
        @method('PUT')

        {!! view_render_event('bagisto.seller.products.edit.edit_form_controls.before', ['product', $product]) !!}

        <!-- Page Header -->
        <div class="grid gap-2.5">
            <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                <div class="grid gap-1.5">
                    <p class="text-2xl font-medium leading-6">
                        @lang('marketplace::app.seller.products.edit.title')
                    </p>
                </div>

                {!! view_render_event('bagisto.seller.products.edit.buttons.before', ['product', $product]) !!}

                <div class="flex items-center gap-x-2.5">
                    {!! view_render_event('bagisto.seller.products.edit.back_button.before', ['product', $product]) !!}

                    <!-- Back Button -->
                    <a
                        href="{{ route('seller.products.index') }}"
                        class="flex gap-x-1.5 items-center place-content-center font-medium rounded-xl p-3 max-w-max cursor-pointer transition-all hover:bg-gray-200"
                    >
                        @lang('marketplace::app.seller.products.edit.back-btn')
                    </a>

                    {!! view_render_event('bagisto.seller.products.edit.back_button.after', ['product', $product]) !!}

                    {!! view_render_event('bagisto.seller.products.edit.preview_button.before', ['product', $product]) !!}

                    <!-- Preview Button -->
                    @if (
                        $product->status
                        && $product->visible_individually
                        && $product->url_key
                    )
                        <a
                            href="{{ route('shop.product_or_category.index', $product->url_key) }}"
                            class="secondary-button font-semibold"
                            target="_blank"
                        >
                            @lang('marketplace::app.seller.products.edit.preview')
                        </a>
                    @endif

                    {!! view_render_event('bagisto.seller.products.edit.preview_button.after', ['product', $product]) !!}

                    {!! view_render_event('bagisto.seller.products.edit.save_button.before', ['product', $product]) !!}
                    
                    <!-- Save Button -->
                    <button
                        type="submit"
                        class="primary-button px-5 py-2.5"
                    >
                        @lang('marketplace::app.seller.products.edit.save-btn')
                    </button>

                    {!! view_render_event('bagisto.seller.products.edit.save_button.after', ['product', $product]) !!}
                </div>

                {!! view_render_event('bagisto.seller.products.edit.buttons.after', ['product', $product]) !!}
            </div>
        </div>

        {!! view_render_event('bagisto.seller.products.edit.local_and_channel_switcher.before', ['product', $product]) !!}

        <!-- Channel and Locale Switcher -->
        <div class="flex items-center justify-between gap-4 max-md:flex-wrap mt-3.5">
            <div class="flex items-center gap-x-5">
                <!-- Channel Switcher -->
                <x-shop::dropdown
                    @class([
                        '[&>*]:!rounded-xl',
                        'hidden' => $channels->count() <= 1,
                    ])
                >
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="flex items-center gap-x-2 p-2 hover:bg-gray-100 focus:bg-gray-100 rounded-xl"
                        >
                            <span class="mp-home-icon text-2xl"></span>
                            
                            {{ $currentChannel->name }}

                            <input
                                type="hidden"
                                name="channel"
                                value="{{ $currentChannel->code }}"
                            />

                            <span class="mp-sort-by-icon text-2xl text-gray-700"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-1">
                        @foreach ($channels as $channel)
                            <a
                                href="?{{ Arr::query(['channel' => $channel->code, 'locale' => $currentLocale->code]) }}"
                                class="flex cursor-pointer gap-2.5 px-5 py-2 text-base hover:bg-gray-100 rounded-xl"
                            >
                                {{ $channel->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-shop::dropdown>

                <!-- Locale Switcher -->
                <x-shop::dropdown
                    @class([
                        '[&>*]:!rounded-xl',
                        'hidden' => $currentChannel->locales->count() <= 1,
                    ])
                >
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="flex items-center gap-x-2 p-2 hover:bg-gray-100 focus:bg-gray-100 rounded-xl"
                        >
                            <span class="mp-language-icon text-2xl"></span>

                            {{ $currentLocale->name }}
                            
                            <input
                                type="hidden"
                                name="locale"
                                value="{{ $currentLocale->code }}"
                            />

                            <span class="mp-sort-by-icon text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-1">
                        @foreach ($currentChannel->locales->sortBy('name') as $locale)
                            <a
                                href="?{{ Arr::query(['channel' => $currentChannel->code, 'locale' => $locale->code]) }}"
                                class="flex gap-2.5 px-5 py-2 text-base cursor-pointer hover:bg-gray-100 rounded-xl"
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-shop::dropdown>
            </div>
        </div>

        {!! view_render_event('bagisto.seller.products.edit.local_and_channel_switcher.after', ['product', $product]) !!}

        @if (core()->getConfigData('marketplace.settings.product.show_progress_bar'))
            <div class="mt-3.5 rounded-xl border bg-white p-5">
                <div class="relative h-4 w-full rounded bg-gray-200">
                    <div
                        class="absolute h-4 rounded bg-green-500"
                        style="width: {{ $productProgressBar->progress }}%;"
                    >
                        <span class="absolute inset-0 flex items-center justify-center text-sm text-white font-medium">
                            {{ $productProgressBar->progress }}%
                        </span>
                    </div>
                </div>
            
                <p class="mt-2.5 text-sm text-gray-800">
                    @lang('marketplace::app.seller.products.edit.progress-info')
                </p>
            </div>        
        @endif

        {!! view_render_event('bagisto.seller.products.edit.progress_bar.after', ['product', $product]) !!}

        <div class="mt-3.5 flex gap-8 max-xl:flex-wrap">
            @foreach ($product->attribute_family->attribute_groups->groupBy('column') as $column => $groups)
                {!! view_render_event('bagisto.seller.product.edit.form.column_' . $column . '.before', ['product' => $product]) !!}

                <div
                    @if ($column == 1) class="flex flex-1 flex-col gap-8 max-xl:flex-auto" @endif
                    @if ($column == 2) class="flex w-[360px] max-w-full flex-col gap-8 max-sm:w-full" @endif
                >
                    @foreach ($groups as $group)
                        @php
                            $customAttributes = $product->getEditableAttributes($group);
                        @endphp

                        @if (count($customAttributes))
                            {!! view_render_event('bagisto.seller.product.edit.form.' . $group->code . '.before', ['product' => $product]) !!}

                            <div class="relative rounded-xl border bg-white p-5">
                                <p class="mb-4 text-base font-semibold text-gray-800">
                                    {{ $group->name }}
                                </p>

                                @foreach ($customAttributes as $attribute)
                                    {!! view_render_event('bagisto.seller.product.edit.form.' . $group->code . '.controls.before', ['product' => $product]) !!}
                                    
                                    <x-marketplace::seller.form.control-group>
                                        <x-marketplace::seller.form.control-group.label class="!mt-5">
                                            {!! $attribute->admin_name . ($attribute->is_required ? '<span class="required"></span>' : '') !!}

                                            @if (
                                                $attribute->value_per_channel
                                                && $channels->count() > 1
                                            )
                                                <span class="rounded border bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-800">
                                                    {{ $currentChannel->name }}
                                                </span>
                                            @endif

                                            @if ($attribute->value_per_locale)
                                                <span class="rounded border bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-800">
                                                    {{ $currentLocale->name }}
                                                </span>
                                            @endif
                                        </x-marketplace::seller.form.control-group.label>

                                        @include ('marketplace::seller.products.edit.controls', [
                                            'attribute' => $attribute,
                                            'product'   => $product,
                                        ])
                                         
                                        <x-marketplace::seller.form.control-group.error :control-name="$attribute->code" />
                                    </x-marketplace::seller.form.control-group>

                                    {!! view_render_event('bagisto.seller.product.edit.form.' . $group->code . '.controls.after', ['product' => $product]) !!}
                                @endforeach

                                @includeWhen($group->code == 'price', 'marketplace::seller.products.edit.price.group')

                                @includeWhen(
                                    $group->code == 'inventories' && ! $product->getTypeInstance()->isComposite(),
                                    'marketplace::seller.products.edit.inventories'
                                )
                            </div>

                            {!! view_render_event('bagisto.seller.product.edit.form.' . $group->code . '.after', ['product' => $product]) !!}
                        @endif
                    @endforeach

                    @if ($column == 1)
                        <!-- Images View Blade File -->
                        @include('marketplace::seller.products.edit.images')

                        <!-- Videos View Blade File -->
                        @include('marketplace::seller.products.edit.videos')

                        <!-- Product Type View Blade File -->
                        @includeIf('marketplace::seller.products.edit.types.'.$product->type)

                        <!-- Include Product Type Additional Blade Files If Any -->
                        @foreach ($product->getTypeInstance()->getAdditionalViews() as $view)
                            @includeIf($view)
                        @endforeach
                    @else
                        <!-- Categories View Blade File -->
                        @include('marketplace::seller.products.edit.categories')

                        @include('marketplace::seller.products.edit.channels')
                    @endif
                </div>

                {!! view_render_event('bagisto.seller.product.edit.form.column_' . $column . '.after', ['product' => $product]) !!}
            @endforeach
        </div>

        {!! view_render_event('bagisto.seller.products.edit.edit_form_controls.after', ['product', $product]) !!}
    </x-marketplace::seller.form>   
    
    {!! view_render_event('bagisto.seller.products.edit.after', ['product', $product]) !!}
</x-marketplace::seller.layouts>