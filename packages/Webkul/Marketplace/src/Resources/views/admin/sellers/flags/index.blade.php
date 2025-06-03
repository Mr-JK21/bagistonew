<x-admin::layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.sellers.flags.index.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-2.5 text-xl font-bold text-gray-800 dark:text-white">
            @lang('marketplace::app.admin.sellers.flags.index.title')
        </p>
    </div>
    
    {!! view_render_event('marketplace.admin.sellers.flags.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.marketplace.sellers.flags.index', $seller->seller_id) }}" />
    
    {!! view_render_event('marketplace.admin.sellers.flags.list.after') !!}
</x-admin::layouts>
