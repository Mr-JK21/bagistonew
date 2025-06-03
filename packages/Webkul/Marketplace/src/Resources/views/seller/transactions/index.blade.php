<x-marketplace::seller.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.seller.transactions.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="seller_transactions" />
    @endSection

    <!-- Page Header -->
    <h2 class="text-2xl font-medium">
        @lang('marketplace::app.seller.transactions.index.title')
    </h2>

    {!! view_render_event('bagisto.seller.transactions.stats.before') !!}

    <div class="mt-8 grid gap-4 rounded-lg border p-5 md:grid-cols-3 md:gap-8">
        {!! view_render_event('bagisto.seller.transactions.stats.total_seller_sale.before') !!}

        <div class="grid py-2.5">
            <h3 class="text-2xl font-medium">
                {{ core()->formatBasePrice($statistics['total_seller_sale']) }}
            </h3>

            <p class="text-sm font-normal text-[#757575]">
                @lang('marketplace::app.seller.transactions.index.total-seller-sale')
            </p>
        </div>

        {!! view_render_event('bagisto.seller.transactions.stats.total_seller_sale.after') !!}

        {!! view_render_event('bagisto.seller.transactions.stats.total_commission.before') !!}

        <div class="grid py-2.5">
            <h3 class="text-2xl font-medium">
                {{ core()->formatBasePrice($statistics['total_commission']) }}
            </h3>

            <p class="text-sm font-normal text-[#757575]">
                @lang('marketplace::app.seller.transactions.index.total-admin-commission')
            </p>
        </div>

        {!! view_render_event('bagisto.seller.transactions.stats.total_commission.after') !!}

        {!! view_render_event('bagisto.seller.transactions.stats.total_sale.before') !!}

        <div class="grid py-2.5">
            <h3 class="text-2xl font-medium">
                {{ core()->formatBasePrice($statistics['total_sale']) }}
            </h3>

            <p class="text-sm font-normal text-[#757575]">
                @lang('marketplace::app.seller.transactions.index.total-sale-invoiced')
            </p>
        </div>

        {!! view_render_event('bagisto.seller.transactions.stats.total_sale.after') !!}

        {!! view_render_event('bagisto.seller.transactions.stats.total_payout.before') !!}

        <div class="grid py-2.5">
            <h3 class="text-2xl font-medium">
                {{ core()->formatBasePrice($statistics['total_payout']) }}
                @php
                    $payoutPercentage = 0;

                    if ($statistics['total_payout']) {
                        $payoutPercentage = ($statistics['total_payout'] / $statistics['total_seller_sale']) * 100;
                    }
                @endphp
                <span class="text-sm font-normal">
                    {{ number_format($payoutPercentage, 2) }} %
                </span>
            </h3>

            <p class="text-sm font-normal text-[#757575]">
                @lang('marketplace::app.seller.transactions.index.total-payout')
            </p>
        </div>

        {!! view_render_event('bagisto.seller.transactions.stats.total_payout.after') !!}

        {!! view_render_event('bagisto.seller.transactions.stats.remaining_payout.before') !!}

        <div class="grid py-2.5">
            <h3 class="text-2xl font-medium">
                {{ core()->formatBasePrice($statistics['remaining_payout']) }}
                @php
                    $remainingPercentage = 0;
    
                    if ($statistics['remaining_payout']) {
                        $remainingPercentage = $statistics['remaining_payout'] * 100 / $statistics['total_sale'];
                    }
                @endphp

                <span class="text-sm font-normal">
                    {{ number_format($remainingPercentage, 2) }} %
                </span>
            </h3>

            <p class="text-sm font-normal text-[#757575]">
                @lang('marketplace::app.seller.transactions.index.remaining-payout')
            </p>
        </div>
            
        {!! view_render_event('bagisto.seller.transactions.stats.remaining_payout.after') !!}
    </div>

    {!! view_render_event('bagisto.seller.transactions.stats.after') !!}

    {!! view_render_event('bagisto.seller.transactions.list.before') !!}

    <!-- Datagrid -->
    <x-shop::datagrid :src="route('seller.transactions.index')" />

    {!! view_render_event('bagisto.seller.transactions.list.after') !!}

</x-marketplace::seller.layouts>