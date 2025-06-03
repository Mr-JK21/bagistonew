<x-marketplace::seller.layouts>    
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.seller.settings.roles.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="roles" />
    @endSection

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <!-- Page Title -->
        <h2 class="text-2xl font-medium">
            @lang('marketplace::app.seller.settings.roles.index.title')
        </h2>

        {!! view_render_event('bagisto.seller.settings.roles.create_btn.before') !!}

        @if (seller()->hasPermission('roles.create'))
            <a
                href="{{ route('seller.settings.roles.create') }}"
                class="primary-button px-5 py-2.5"
            >
                @lang('marketplace::app.seller.settings.roles.index.create-btn')
            </a>
        @endif

        {!! view_render_event('bagisto.seller.settings.roles.create_btn.after') !!}
    </div>

    {!! view_render_event('bagisto.seller.settings.roles.list.before') !!}
    
    <!-- Datagrid -->
    <x-shop::datagrid :src="route('seller.settings.roles.index')" />
    
    {!! view_render_event('bagisto.seller.settings.roles.list.after') !!}
</x-marketplace::seller.layouts>
