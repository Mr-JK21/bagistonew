<div class="relative rounded-xl border max-sm:overflow-x-auto md:mt-6">
    <table class="w-full text-left text-sm">
        <thead class="border-b border-[#E9E9E9] bg-[#F5F5F5] text-sm text-black">
            <tr>
                <th
                    scope="col"
                    class="w-3/5 px-6 py-4 font-medium"
                >
                    @lang('marketplace::app.seller.orders.view.product')
                </th>

                <th
                    scope="col"
                    class="px-6 py-4 font-medium"
                >
                    @lang('marketplace::app.seller.orders.view.price')
                </th>

                <th
                    scope="col"
                    class="px-6 py-4 font-medium"
                >
                    @lang('marketplace::app.seller.orders.view.item-status')
                </th>

                <th
                    scope="col"
                    class="px-6 py-4 font-medium"
                >
                    @lang('marketplace::app.seller.orders.view.sub-total')
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sellerOrder->items as $orderItem)
                <tr class="border-b bg-white">
                    <td
                        class="px-6 py-4 font-medium text-black"
                        data-value="@lang('marketplace::app.seller.orders.view.product')"
                    >
                        <div class="flex gap-2">
                            <x-shop::media.images.lazy
                                class="h-15 min-w-15 max-w-15 rounded-lg object-cover"
                                :src="$orderItem->item->product?->base_image_url ?: bagisto_asset('images/small-product-placeholder-64b7f208.webp', 'marketplace')"
                                alt="Product Image"
                                width="60"
                                height="60"
                            >
                            </x-shop::media.images.lazy>

                            <div class="flex flex-col">
                                <p class="text-sm font-medium">
                                    {{ $orderItem->item->name }}
                                </p>
                                
                                <p class="text-sm font-normal">
                                    @lang('marketplace::app.seller.orders.view.sku', ['sku' => $orderItem->item->sku])
                                </p>

                                @if (isset($orderItem->item->additional['attributes']))
                                    <div>
                                        @foreach ($orderItem->item->additional['attributes'] as $attribute)
                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}<br>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td
                        class="px-6 py-4 font-medium text-black"
                        data-value="@lang('marketplace::app.seller.orders.view.price')"
                    >
                        {{ core()->formatBasePrice($orderItem->item->base_price) }}
                    </td>

                    <td
                        class="px-6 py-4 font-medium text-black"
                        data-value= "@lang('marketplace::app.seller.orders.view.item-status')"
                    >
                        <div class="grid">
                            <span>
                                @lang('marketplace::app.seller.orders.view.item-ordered', ['qty_ordered' => $orderItem->item->qty_ordered])
                            </span>

                            <span>
                                {{ $orderItem->item->qty_invoiced ? __('marketplace::app.seller.orders.view.item-invoice', ['qty_invoiced' => $orderItem->item->qty_invoiced]) : '' }}
                            </span>

                            <span>
                                {{ $orderItem->item->qty_shipped ? __('marketplace::app.seller.orders.view.item-shipped', ['qty_shipped' => $orderItem->item->qty_shipped]) : '' }}
                            </span>

                            <span>
                                {{ $orderItem->item->qty_refunded ? __('marketplace::app.seller.orders.view.item-refunded', ['qty_refunded' => $orderItem->item->qty_refunded]) : '' }}
                            </span>

                            <span>
                                {{ $orderItem->item->qty_canceled ? __('marketplace::app.seller.orders.view.item-canceled', ['qty_canceled' => $orderItem->item->qty_canceled]) : '' }}
                            </span>
                        </div>
                    </td>

                    <td
                        class="px-6 py-4 font-medium text-black"
                        data-value="@lang('marketplace::app.seller.orders.view.sub-total')"
                    >
                        <div class="flex items-center gap-2">
                            {{ core()->formatBasePrice($orderItem->item->base_total) }}

                            <x-shop::dropdown>
                                <x-slot:toggle>
                                    <span class="mp-toast-info-icon cursor-pointer text-2xl"></span>
                                </x-slot:toggle>

                                <x-slot:content class="bg-[#000000] bg-opacity-80 text-white">
                                    <div class="grid gap-2">
                                        <div class="flex w-full justify-between gap-5">
                                            <p class="text-sm">
                                                @lang('marketplace::app.seller.orders.view.total')
                                            </p>
        
                                            <div class="flex gap-5">
                                                <p class="text-sm">-</p>
        
                                                <p class="text-sm">
                                                    {{ core()->formatBasePrice($orderItem->base_seller_total) }}
                                                </p>
                                            </div>
                                        </div>
        
                                        <div class="flex w-full justify-between gap-5">
                                            <p class="text-sm">
                                                @lang('marketplace::app.seller.orders.view.discount')
    
                                                @if ($sellerOrder->order->coupon_code)
                                                    ({{ $sellerOrder->order->coupon_code }})
                                                @endif
                                            </p>
    
                                            <div class="flex gap-5">
                                                <p class="text-sm">-</p>
    
                                                <p class="text-sm">
                                                    {{ core()->formatBasePrice($orderItem->item->base_discount_amount) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex w-full justify-between gap-5">
                                            <p class="text-sm">
                                                @lang('marketplace::app.seller.orders.view.admin-commission')
                                            </p>
        
                                            <div class="flex gap-5">
                                                <p class="text-sm">-</p>
        
                                                <p class="text-sm">
                                                    {{ core()->formatBasePrice($orderItem->base_commission) }}
                                                </p>
                                            </div>
                                        </div>
        
                                        <div class="flex w-full justify-between gap-5">
                                            <p class="text-sm">
                                                @lang('marketplace::app.seller.orders.view.tax')
                                            </p>
        
                                            <div class="flex gap-5">
                                                <p class="text-sm">-</p>
        
                                                <p class="text-sm">
                                                    {{ core()->formatBasePrice($orderItem->item->base_tax_amount) }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        @if (core()->getConfigData('sales.taxes.sales.display_prices') != 'including_tax')
                                            <div class="flex w-full justify-between gap-5">
                                                <p class="text-sm">
                                                    @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                                        @lang('marketplace::app.seller.orders.view.price-excl-tax')
                                                    @else
                                                        @lang('marketplace::app.seller.orders.view.price')
                                                    @endif
                                                </p>
            
                                                <div class="flex gap-5">
                                                    <p class="text-sm">-</p>
                                                    
                                                    <p class="text-sm">
                                                        {{ core()->formatBasePrice($orderItem->item->base_price) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax'
                                            || core()->getConfigData('sales.taxes.sales.display_prices') == 'both'
                                        )
                                            <div class="flex w-full justify-between gap-5">
                                                <p class="text-sm">
                                                    @lang('marketplace::app.seller.orders.view.price-incl-tax')
                                                </p>
            
                                                <div class="flex gap-5">
                                                    <p class="text-sm">-</p>
                                                    
                                                    <p class="text-sm">
                                                        {{ core()->formatBasePrice($orderItem->item->base_price_incl_tax) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Sub Total -->
                                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') != 'including_tax')
                                            <div class="flex w-full justify-between gap-5">
                                                <p class="text-sm">
                                                    @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both')
                                                        @lang('marketplace::app.seller.orders.view.sub-total-excl-tax')
                                                    @else
                                                        @lang('marketplace::app.seller.orders.view.sub-total')
                                                    @endif
                                                </p>
            
                                                <div class="flex gap-5">
                                                    <p class="text-sm">-</p>
                                                    
                                                    <p class="text-sm">
                                                        {{ core()->formatBasePrice($orderItem->item->base_total) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax'
                                            || core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both'
                                        )
                                            <div class="flex w-full justify-between gap-5">
                                                <p class="text-sm">
                                                    @lang('marketplace::app.seller.orders.view.sub-total-incl-tax')
                                                </p>
            
                                                <div class="flex gap-5">
                                                    <p class="text-sm">-</p>
                                                    
                                                    <p class="text-sm">
                                                        {{ core()->formatBasePrice($orderItem->item->base_total_incl_tax) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </x-slot:content>
                            </x-shop::dropdown>
                        </div>
                    </td>
                </tr>
            @endforeach
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
                                @lang('marketplace::app.seller.orders.view.sub-total-excl-tax')
                            @else
                                @lang('marketplace::app.seller.orders.view.sub-total')
                            @endif
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>
                            
                            <p class="text-sm">
                                {{ core()->formatBasePrice($sellerOrder->base_sub_total) }}
                            </p>
                        </div>
                    </div>
                @endif

                @if (core()->getConfigData('sales.taxes.sales.display_subtotal') == 'including_tax'
                    || core()->getConfigData('sales.taxes.sales.display_subtotal') == 'both'
                )
                    <div class="flex w-full justify-between gap-5">
                        <p class="text-sm">
                            @lang('marketplace::app.seller.orders.view.sub-total-incl-tax')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>
                            
                            <p class="text-sm">
                                {{ core()->formatBasePrice($sellerOrder->base_sub_total_incl_tax) }}
                            </p>
                        </div>
                    </div>
                @endif

                @if ($sellerOrder->order->haveStockableItems())
                    <!-- Shipping Handling -->
                    @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') != 'including_tax')
                        <div class="flex w-full justify-between gap-5">
                            <p class="text-sm">
                                @if (core()->getConfigData('sales.taxes.sales.display_shipping_amount') == 'both')
                                    @lang('marketplace::app.seller.orders.view.shipping-handling-excl-tax')
                                @else
                                    @lang('marketplace::app.seller.orders.view.shipping-handling')
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
                                @lang('marketplace::app.seller.orders.view.shipping-handling-incl-tax')
                            </p>

                            <div class="flex gap-5">
                                <p class="text-sm">-</p>
                                
                                <p class="text-sm">
                                    {{ core()->formatBasePrice(0) }}
                                </p>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="flex w-full justify-between gap-5">
                    <p class="text-sm">
                        @lang('marketplace::app.seller.orders.view.discount')

                        @if ($sellerOrder->order->coupon_code)
                            ({{ $sellerOrder->order->coupon_code }})
                        @endif
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatBasePrice($sellerOrder->base_discount_amount) }}
                        </p>
                    </div>
                </div>

                <div class="flex w-full justify-between gap-5">
                    <p class="text-sm">
                        @lang('marketplace::app.seller.orders.view.tax')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatBasePrice($sellerOrder->base_tax_amount) }}
                        </p>
                    </div>
                </div>

                <div class="flex w-full justify-between gap-5">
                    <p class="text-sm">
                        @lang('marketplace::app.seller.orders.view.total-refunded')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>
                        
                        <p class="text-sm">
                            {{ core()->formatBasePrice($sellerOrder->base_grand_total_refunded - $sellerOrder->base_shipping_refunded) }}
                        </p>
                    </div>
                </div>
                
                <div class="flex w-full justify-between gap-5">
                    <p class="text-sm">
                        @lang('marketplace::app.seller.orders.view.total-seller-amt')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatBasePrice($sellerOrder->base_seller_total) }}
                        </p>
                    </div>
                </div>
                
                <div class="flex w-full justify-between gap-5">
                    <p class="text-sm">
                        @lang('marketplace::app.seller.orders.view.admin-commission')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatBasePrice($sellerOrder->base_commission) }}
                        </p>
                    </div>
                </div>

                <div class="flex w-full justify-between gap-5">
                    <p class="text-sm">
                        @lang('marketplace::app.seller.orders.view.total-paid')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatBasePrice($sellerOrder->base_grand_total_invoiced - $sellerOrder->base_shipping_invoiced) }}
                        </p>
                    </div>
                </div>

                <div class="flex w-full justify-between gap-5">
                    <p class="text-sm">
                        @lang('marketplace::app.seller.orders.view.total-due')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            @if ($sellerOrder->status !== 'canceled')
                                {{ core()->formatBasePrice($sellerOrder->base_total_due ? $sellerOrder->base_total_due : 0) }}
                            @else
                                {{ core()->formatBasePrice(0.00) }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex w-full justify-between gap-5">
                    <p class="text-sm font-semibold">
                        @lang('marketplace::app.seller.orders.view.grand-total')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm font-semibold">
                            {{ core()->formatBasePrice($sellerOrder->base_grand_total - $sellerOrder->base_shipping_amount) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>