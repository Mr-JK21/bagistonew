<x-admin::layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.products.flags.index.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-2.5 text-xl font-bold text-gray-800 dark:text-white">
            @lang('marketplace::app.admin.products.flags.index.title')
        </p>
    </div>
    
    {!! view_render_event('marketplace.admin.products.flags.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.marketplace.products.flags.index', $product->id) }}" />
    
    {!! view_render_event('marketplace.admin.products.flags.list.after') !!}
</x-admin::layouts>
