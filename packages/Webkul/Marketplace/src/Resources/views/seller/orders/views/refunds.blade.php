@foreach ($sellerOrder->refunds as $refund)
    <div class="flex items-center md:mt-6">
        <p class="text-base font-medium">
            @lang('marketplace::app.seller.orders.view.refunds.individual-refund', ['refund_id' => $refund->id]),
        </p>&nbsp;
        <p class="text-base font-medium">
            @lang('marketplace::app.seller.orders.view.refunds.created-on', ['date_time' => core()->formatDate($refund->created_at, 'd/m/y h:i:s')])
        </p>
    </div>

    <div class="relative mt-6 overflow-x-auto rounded-xl border">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-[#E9E9E9] bg-[#F5F5F5] text-sm text-black">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.orders.view.refunds.product-name')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.orders.view.refunds.price')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.orders.view.refunds.qty')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.orders.view.refunds.subtotal')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.orders.view.refunds.tax-amount')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.seller.orders.view.refunds.grand-total')
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse ($refund->items as $refundItem)
                    <tr class="border-b bg-white">
                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.orders.view.refunds.product-name')"
                        >
                            <div class="flex flex-col">
                                <p class="text-sm font-medium">
                                    {{ $refundItem->item->name }}
                                </p>
                                
                                <p class="text-sm font-normal">
                                    @lang('marketplace::app.seller.orders.view.invoices.sku', ['sku' => $refundItem->item->child ? $refundItem->item->child->sku : $refundItem->item->sku])
                                </p>

                                @if (isset($refundItem->item->additional['attributes']))
                                    <div>
                                        @foreach ($refundItem->item->additional['attributes'] as $attribute)
                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}<br>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.orders.view.refunds.price')"
                        >
                            {{ core()->formatBasePrice($refundItem->item->base_price) }}
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.orders.view.refunds.qty')"
                        >
                            {{ $refundItem->item->qty }}
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.orders.view.refunds.subtotal')"
                        >
                            {{ core()->formatBasePrice($refundItem->item->base_total) }}
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.orders.view.refunds.tax-amount')"
                        >
                            {{ core()->formatBasePrice($refundItem->item->base_tax_amount) }}
                        </td>

                        <td
                            class="px-6 py-4 font-medium text-black"
                            data-value="@lang('marketplace::app.seller.orders.view.refunds.grand-total')"
                        >
                            {{ core()->formatBasePrice($refundItem->item->base_total + $refundItem->item->base_tax_amount) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>@lang('marketplace::app.seller.orders.view.refunds.no-result-found')</td>
                    </tr>                    
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex items-start gap-10 max-lg:gap-5 max-md:grid">
        <div class="flex-auto max-md:mt-8">
            <div class="flex justify-end">
                <div class="grid max-w-max gap-2">
                    <!-- Sub Total -->
                    @if (core()->getConfigData('sales.taxes.sales.display_subtotal') != 'including_tax')
                        <div class="flex w-full justify-between gap-5">
                            <p class="text-sm">
                                @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                    @lang('marketplace::app.seller.orders.view.refunds.sub-total-excl-tax')
                                @else
                                    @lang('marketplace::app.seller.orders.view.refunds.subtotal')
                                @endif
                            </p>

                            <div class="flex gap-5">
                                <p class="text-sm">-</p>
                                
                                <p class="text-sm">
                                    {{ core()->formatBasePrice($refund->base_sub_total) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax'
                        || core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both'
                    )
                        <div class="flex w-full justify-between gap-5">
                            <p class="text-sm">
                                @lang('marketplace::app.seller.orders.view.refunds.sub-total-incl-tax')
                            </p>

                            <div class="flex gap-5">
                                <p class="text-sm">-</p>
                                
                                <p class="text-sm">
                                    {{ core()->formatBasePrice($refund->base_sub_total_incl_tax) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Shipping Handling -->
                    @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') != 'including_tax')
                        <div class="flex w-full justify-between gap-5">
                            <p class="text-sm">
                                @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                    @lang('marketplace::app.seller.orders.view.refunds.shipping-handling-excl-tax')
                                @else
                                    @lang('marketplace::app.seller.orders.view.refunds.shipping-handling')
                                @endif
                            </p>

                            <div class="flex gap-5">
                                <p class="text-sm">-</p>
                                
                                <p class="text-sm">
                                    {{ core()->formatBasePrice(0) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'including_tax'
                        || core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both'
                    )
                        <div class="flex w-full justify-between gap-5">
                            <p class="text-sm">
                                @lang('marketplace::app.seller.orders.view.refunds.shipping-handling-incl-tax')
                            </p>

                            <div class="flex gap-5">
                                <p class="text-sm">-</p>

                                <p class="text-sm">
                                    {{ core()->formatBasePrice(0) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if ($refund->discount_amount > 0)
                        <div class="flex w-full justify-between gap-x-5">
                            <p class="text-sm">
                                @lang('marketplace::app.seller.orders.view.refunds.discount')
                            </p>

                            <div class="flex gap-x-5">
                                <p class="text-sm">-</p>

                                <p class="text-sm">
                                    {{ core()->formatBasePrice($sellerOrder->order->base_discount_amount) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if ($refund->tax_amount > 0)
                        <div class="flex w-full justify-between gap-x-5">
                            <p class="text-sm">
                                @lang('marketplace::app.seller.orders.view.refunds.tax')
                            </p>

                            <div class="flex gap-x-5">
                                <p class="text-sm">-</p>

                                <p class="text-sm">
                                    {{ core()->formatBasePrice($refund->base_tax_amount) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="flex w-full justify-between gap-x-5">
                        <p class="text-sm">
                            @lang('marketplace::app.seller.orders.view.refunds.adjustment-refund')
                        </p>

                        <div class="flex gap-x-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatBasePrice($refund->base_adjustment_refund) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex w-full justify-between gap-x-5">
                        <p class="text-sm">
                            @lang('marketplace::app.seller.orders.view.refunds.adjustment-fee')
                        </p>

                        <div class="flex gap-x-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatBasePrice($refund->base_adjustment_fee) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex w-full justify-between gap-x-5">
                        <p class="text-sm font-semibold">
                            @lang('marketplace::app.seller.orders.view.refunds.grand-total')
                        </p>

                        <div class="flex gap-x-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm font-semibold">
                                {{ core()->formatBasePrice($refund->base_grand_total - $refund->base_shipping_amount) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach