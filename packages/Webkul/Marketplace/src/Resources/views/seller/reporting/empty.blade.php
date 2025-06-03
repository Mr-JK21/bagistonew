<div class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10">
    <img
        class="h-[120px] w-[120px] p-2"
        src="{{ bagisto_asset('images/empty-placeholders/report-empty.svg', 'marketplace') }}"
    >

    <div class="flex flex-col items-center gap-1.5">
        <p class="text-base font-semibold text-gray-400">
            @lang('marketplace::app.seller.reporting.empty.title')
        </p>
        
        <p class="text-gray-400">
            @lang('marketplace::app.seller.reporting.empty.info')
        </p>
    </div>
</div>