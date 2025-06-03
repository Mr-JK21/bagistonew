<x-admin::layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.flag-reasons.index.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-2.5 text-xl font-bold text-gray-800 dark:text-white">
            @lang('marketplace::app.admin.flag-reasons.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            {!! view_render_event('marketplace.admin.flag_reasons.index.create_button.before') !!}
            
            @if (bouncer()->hasPermission('marketplace.flag-reasons.create'))
                <a 
                    href="{{ route('admin.marketplace.flag_reasons.create') }}"
                    class="primary-button"
                >
                    @lang('marketplace::app.admin.flag-reasons.index.create-btn')                  
                </a>
            @endif

            {!! view_render_event('marketplace.admin.flag_reasons.index.create_button.after') !!}
        </div>
    </div>
    
    {!! view_render_event('marketplace.admin.flag_reasons.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.marketplace.flag_reasons.index') }}" />
    
    {!! view_render_event('marketplace.admin.flag_reasons.list.after') !!}
</x-admin::layouts>
