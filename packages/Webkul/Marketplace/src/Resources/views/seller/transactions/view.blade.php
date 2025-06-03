<x-marketplace::seller.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.seller.transactions.view.title', ['transaction_id' => $transaction->transaction_id])
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="seller_transactions_view" />
    @endSection

    {!! view_render_event('bagisto.seller.transaction.view.before', ['transaction' => $transaction]) !!}

    <!-- Page Header -->
    <h2 class="text-2xl font-medium">
        @lang('marketplace::app.seller.transactions.view.title', ['transaction_id' => $transaction->transaction_id])
    </h2>

    <div class="mt-4 flex items-center justify-between">
        <div class="flex items-center gap-2 py-4">
            <span class="flex h-5 items-center rounded-xl bg-[#02B1FD] p-2.5 text-xs font-medium text-white">
                @lang('marketplace::app.seller.transactions.view.payment-method', ['method' => $transaction->method])
            </span>

            <span class="text-xs font-medium opacity-80">
                @lang('marketplace::app.seller.transactions.view.created-on', ['date' => core()->formatDate($transaction->created_at, 'd M Y')])
            </span>
        </div>

        {!! view_render_event('bagisto.seller.transaction.view.print_button.before', ['transaction' => $transaction]) !!}

        @if (seller()->hasPermission('transactions.print'))
            <a
                href="{{route('seller.transactions.print', $transaction->id)}}"
                class="secondary-button flex items-center gap-x-2.5 border-[#E9E9E9] px-5 py-3 font-normal"
            >
                @lang('marketplace::app.seller.transactions.view.print')
            </a>
        @endif

        {!! view_render_event('bagisto.seller.transaction.view.print_button.after', ['transaction' => $transaction]) !!}
    </div>
    
    {!! view_render_event('bagisto.seller.transaction.view.list.before', ['transaction' => $transaction]) !!}

    <div class="relative mt-4 overflow-x-auto rounded-xl border">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-[#E9E9E9] bg-[#F5F5F5] text-sm text-black">
                <tr>
                    {!! view_render_event('marketplace.seller.transaction.list.heading_row.before') !!}
                    
                    <th
                        scope="col"
                        class="w-3/5 px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.transactions.view.name')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.transactions.view.price')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.transactions.view.qty')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.transactions.view.total')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.transactions.view.commission')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.transactions.view.seller-total')
                    </th>

                    {!! view_render_event('marketplace.seller.transaction.list.heading_row.after') !!}
                </tr>
            </thead>

            <tbody>
                @foreach ($transaction->order->items as $item)
                    <tr class="border-b bg-white">
                        {!! view_render_event('marketplace.seller.transaction.list.record_row.before', ['item' => $item]) !!}
                        
                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.transactions.view.name')"
                        >
                            {{ $item->item->name }}
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.transactions.view.price')"
                        >
                            {{ core()->formatBasePrice($item->item->base_price) }}
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value= "@lang('marketplace::app.seller.transactions.view.item-status')"
                        >
                            @if (in_array($item->item->type, ['downloadable', 'virtual']))
                                {{ 'N/A' }}
                            @else
                                {{ $item->item->qty_shipped}}
                            @endif
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.transactions.view.sub-total')"
                        >
                            <div class="flex items-center gap-2">
                                {{ core()->formatBasePrice($item->item->base_total) }}
                            </div>
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.transactions.view.sub-total')"
                        >
                            <div class="flex items-center gap-2">
                                {{ core()->formatBasePrice($item->base_commission) }}
                            </div>
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.transactions.view.sub-total')"
                        >
                            <div class="flex items-center gap-2">
                                {{ core()->formatBasePrice($item->base_seller_total) }}
                            </div>
                        </td>

                        {!! view_render_event('marketplace.seller.transaction.list.record_row.after', ['item' => $item]) !!}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {!! view_render_event('bagisto.seller.transaction.view.list.after', ['transaction' => $transaction]) !!}

    <div class="mt-8 flex items-start gap-10 max-lg:gap-5 max-md:grid">
        <div class="flex-auto max-md:mt-8">
            <div class="flex justify-end">
                <div class="grid max-w-max gap-2">
                    <div class="flex w-full justify-between gap-5">
                        <p class="text-sm">
                            @lang('marketplace::app.seller.transactions.view.subtotal')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatBasePrice($transaction->order->base_sub_total) }}
                            </p>
                        </div>
                    </div>

                    @if ($transaction->order->order->haveStockableItems())
                        <div class="flex w-full justify-between gap-5">
                            <p class="text-sm">
                                @lang('marketplace::app.seller.transactions.view.shipping-handling')
                            </p>

                            <div class="flex gap-5">
                                <p class="text-sm">-</p>

                                <p class="text-sm">
                                    {{ core()->formatBasePrice(0) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="flex w-full justify-between gap-5">
                        <p class="text-sm">
                            @lang('marketplace::app.seller.transactions.view.tax')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatBasePrice($transaction->order->base_tax_amount) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex w-full justify-between gap-5">
                        <p class="text-sm">
                            @lang('marketplace::app.seller.transactions.view.commission')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatBasePrice($transaction->order->base_commission) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex w-full justify-between gap-5">
                        <p class="text-sm font-semibold">
                            @lang('marketplace::app.seller.transactions.view.seller-total')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm font-semibold">
                                {{ core()->formatBasePrice($transaction->order->base_seller_total) }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.seller.transaction.view.after', ['transaction' => $transaction]) !!}
</x-marketplace::seller.layouts>