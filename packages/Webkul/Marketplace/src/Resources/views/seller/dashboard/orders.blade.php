<!-- Order Details Vue Component -->
<v-dashboard-orders-details>
    <!-- Shimmer -->
    <x:marketplace::seller.shimmer.dashboard.orders />
</v-dashboard-orders-details>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-orders-details-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x:marketplace::seller.shimmer.dashboard.orders />
        </template>

        <!-- Order Details -->
        <template v-else>
            {!! view_render_event('marketplace.seller.dashboard.orders.tabs.before') !!}

            <div class="mt-8 flex max-sm:flex-wrap">
                {!! view_render_event('bagisto.seller.dashboard.orders.tabs.pending_tab.before') !!}
                
                <p
                    class="cursor-pointer px-4 py-2 text-lg font-medium leading-10"
                    :class="{ 'border-b-2 border-[#060C3B]': activeTab == 'pending' }"
                    @click="activeTab = 'pending'"
                >
                    @{{ "@lang('marketplace::app.seller.dashboard.pending-orders')".replace(':count', report.statistics['pending'].length) }}
                </p>

                {!! view_render_event('bagisto.seller.dashboard.orders.tabs.pending_tab.after') !!}

                {!! view_render_event('bagisto.seller.dashboard.orders.tabs.processing_tab.before') !!}
                
                <p
                    class="cursor-pointer px-4 py-2 text-lg font-medium leading-10"
                    :class="{ 'border-b-2 border-[#060C3B]': activeTab == 'processing' }"
                    @click="activeTab = 'processing'"
                >
                    @{{ "@lang('marketplace::app.seller.dashboard.processing-orders')".replace(':count', report.statistics['processing'].length) }}
                </p>

                {!! view_render_event('bagisto.seller.dashboard.orders.tabs.processing_tab.after') !!}

                {!! view_render_event('bagisto.seller.dashboard.orders.tabs.completed_tab.before') !!}
                
                <p
                    class="cursor-pointer px-4 py-2 text-lg font-medium leading-10"
                    :class="{ 'border-b-2 border-[#060C3B]': activeTab == 'completed' }"
                    @click="activeTab = 'completed'"
                >
                    @{{ "@lang('marketplace::app.seller.dashboard.completed-orders')".replace(':count', report.statistics['completed'].length) }}
                </p>

                {!! view_render_event('bagisto.seller.dashboard.orders.tabs.completed_tab.after') !!}

                {!! view_render_event('bagisto.seller.dashboard.orders.tabs.refunded_tab.before') !!}
                
                <p
                    class="cursor-pointer px-4 py-2 text-lg font-medium leading-10"
                    :class="{ 'border-b-2 border-[#060C3B]': activeTab == 'refunded' }"
                    @click="activeTab = 'refunded'"
                >
                    @{{ "@lang('marketplace::app.seller.dashboard.refunded-orders')".replace(':count', report.statistics['refunded'].length) }}
                </p>

                {!! view_render_event('bagisto.seller.dashboard.orders.tabs.refunded_tab.after') !!}
            </div>

            {!! view_render_event('bagisto.seller.dashboard.orders.tabs.after') !!}

            {!! view_render_event('bagisto.seller.dashboard.orders.list.before') !!}
            
            <v-order-table :statistics="report.statistics" />

            {!! view_render_event('bagisto.seller.dashboard.orders.list.after') !!}
        </template>
    </script>

    <script
        type="text/x-template"
        id="v-order-table-template"
    >
        <div class="w-full overflow-x-auto rounded-xl border">
            <div class="table-responsive grid w-full overflow-hidden rounded bg-white">
                <div class="row grid grid-cols-[0.5fr_0.6fr_0.5fr_0.5fr_0.3fr_0.1fr] grid-rows-1 items-center border-b bg-[#F5F5F5] px-4 py-2.5">
                    <div
                        class="flex select-none items-center gap-2.5"
                        v-for="columnGroup in [['id', 'date', 'status'], ['customer', 'email', 'location'], ['items', 'payment', 'shipment'], ['gross-amt', 'discount', 'commission'], ['seller-earn', 'status']]"
                    >
                        <p class="text-sm font-medium text-[#000000]">
                            <span class="[&>*]:after:content-['_/_']">
                                <span
                                    class="after:content-['/'] last:after:content-['']"
                                    v-for="column in columnGroup"
                                >
                                    @{{ columnNames[column] }}
                                </span>
                            </span>
                        </p>
                    </div>
                </div>
                
                <div
                    class="row grid grid-cols-[0.5fr_0.6fr_0.5fr_0.5fr_0.3fr_0.1fr] grid-rows-1 items-center border-b px-4 py-2.5"
                    v-for="order in orders"
                >
                    <!-- Order Id, Created, Status Section -->
                    <div class="grid gap-y-1.5">
                        <p class="text-sm font-semibold">
                            @{{ "@lang('marketplace::app.seller.orders.index.datagrid.order-id')".replace(':id', order.increment_id) }}
                        </p>
            
                        <p class="text-sm">
                            @{{ order.created_at }}
                        </p>
            
                        <p class="text-sm">
                            <template v-if="order.status == 'processing'">
                                <span class="label-active">
                                    @lang('marketplace::app.seller.orders.index.datagrid.processing')
                                </span>
                            </template>
    
                            <template v-if="order.status == 'completed'">
                                <span class="label-active">
                                    @lang('marketplace::app.seller.orders.index.datagrid.completed')
                                </span>
                            </template>
    
                            <template v-if="order.status == 'canceled'">
                                <span class="label-cancelled">
                                    @lang('marketplace::app.seller.orders.index.datagrid.canceled')
                                </span>
                            </template>
    
                            <template v-if="order.status == 'closed'">
                                <span class="label-closed">
                                    @lang('marketplace::app.seller.orders.index.datagrid.closed')
                                </span>
                            </template>
    
                            <template v-if="order.status == 'pending'">
                                <span class="label-pending">
                                    @lang('marketplace::app.seller.orders.index.datagrid.pending')
                                </span>
                            </template>
    
                            <template v-if="order.status == 'pending_payment'">
                                <span class="label-pending">
                                    @lang('marketplace::app.seller.orders.index.datagrid.pending-payment')
                                </span>
                            </template>
    
                            <template v-if="order.status == 'fraud'">
                                <span class="label-closed">
                                    @lang('marketplace::app.seller.orders.index.datagrid.fraud')
                                </span>
                            </template>

                            {!! view_render_event('marketplace.seller.dashboard.stats.orders.status.label') !!}
                        </p>
                    </div>
            
                    <!-- Customer Name, Email, Location Section -->
                    <div class="grid gap-y-1.5">
                        <p class="text-sm font-semibold">
                            @{{ order.customer_name }}
                        </p>
            
                        <p class="text-sm">
                            @{{ order.customer_email }}
                        </p>
            
                        <p class="text-sm">
                            @{{ order.location }}
                        </p>
                    </div>
            
                    <!-- Items, Payment, Shipping -->
                    <div class="grid gap-y-1.5">
                        <p class="text-sm font-semibold">
                            @{{ order.total_item_count }}
                        </p>
            
                        <p class="text-sm">
                            @{{ order.payment_method }}
                        </p>
            
                        <p class="text-sm">
                            @{{ order.shipping_method }}
                        </p>
                    </div>
            
                    <!-- Base Total Amount, Discount, Commission -->
                    <div class="grid gap-y-1.5">
                        <p class="text-sm font-semibold">
                            @{{ $shop.formatPrice(order.base_sub_total) }}
                        </p>
            
                        <p class="text-sm">
                            @{{ $shop.formatPrice(order.base_discount_amount) }}
                        </p>
            
                        <p class="text-sm">
                            @{{ $shop.formatPrice(order.base_commission) }}
                        </p>
                    </div>
            
                    <!-- Seller Total Amount, Status -->
                    <div class="grid gap-y-1.5">
                        <p class="text-sm font-semibold">
                            @{{ $shop.formatPrice(order.base_seller_total) }}
                        </p>
            
                        @if (seller()->hasPermission('orders.payment_request'))
                            <p class="text-sm">
                                <template v-if="order.seller_payout_status == 'paid'">
                                    <div class="grid gap-y-1.5">
                                        <span class="label-active">
                                            @lang('marketplace::app.seller.orders.index.datagrid.paid')
                                        </span>
                
                                        <a
                                            :href="`{{ route('seller.transactions.view', '') }}/${order.marketplace_transaction_id}`"
                                            class="text-[#0A49A7]"
                                        >
                                            @lang('marketplace::app.seller.orders.index.datagrid.transaction')
                                        </a>
                                    </div>
                                </template>
                        
                                <template v-else-if="order.seller_payout_status == 'refunded'">
                                    <span class="label-closed">
                                        @lang('marketplace::app.seller.orders.index.datagrid.refunded')
                                    </span>
                                </template>
        
                                <template v-else-if="order.seller_payout_status == 'requested'">
                                    <span class="label-pending">
                                        @lang('marketplace::app.seller.orders.index.datagrid.requested')
                                    </span>
                                </template>
                        
                                <template v-else>        
                                    <template v-if="order.base_total ? order.base_seller_total_invoiced - order.base_total : order.base_seller_total_invoiced && order.status == 'completed'">
                                        <a
                                            :href="`{{ route('seller.payment.request', '') }}/${order.marketplace_order_id}`" 
                                            class="text-[#0A49A7]"
                                        >
                                            @lang('marketplace::app.seller.orders.index.datagrid.payment-request')
                                        </a>
                                    </template>
        
                                    <template v-else-if="order.base_total ? order.base_seller_total_invoiced - order.base_total : order.base_seller_total_invoiced && order.status == 'processing'">
                                        <span class="label-active">
                                            @lang('marketplace::app.seller.orders.index.datagrid.processing')
                                        </span>
                                    </template>
        
                                    <template v-else-if="order.status == 'canceled'">
                                        <span class="label-cancelled">
                                            @lang('marketplace::app.seller.orders.index.datagrid.canceled')
                                        </span>
                                    </template>
        
                                    <template v-else>
                                        <span class="label-pending">
                                            @lang('marketplace::app.seller.orders.index.datagrid.invoice-pending')
                                        </span>
                                    </template>
                                </template>
                            </p>
                        @endif
                    </div>
            
                    <!-- Action -->
                    @if (seller()->hasPermission('orders.view'))
                        <div class="flex items-center justify-between gap-x-4">
                            <a :href="`{{ route('seller.orders.view', '') }}/${order.order_id}`">
                                <span
                                    class="icon-arrow-right cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 max-sm:place-self-center"
                                    title="@lang('marketplace::app.seller.orders.index.datagrid.view')"
                                >
                                </span>
                            </a>
                        </div>
                    @endif
                </div>
    
                <div class="flex items-center justify-between p-4">
                    <template v-if="orders.length">
                        <p class="py-1.5 text-xs font-medium">
                            Showing @{{ orders.length ?? 1 }} to @{{ orders.length }} of @{{ orders.length }} entries
                        </p>
                    </template>
                    
                    <template v-else>
                        <p class="flex w-full justify-center text-gray-600">
                            @lang('marketplace::app.seller.dashboard.no-record-available')
                        </p>
                    </template>
                
                    <template v-if="orders.length >= 5">
                        <div class="secondary-button px-10 py-1.5">
                            <a href="{{ route('seller.orders.index') }}">
                                @lang('marketplace::app.seller.dashboard.view-all-btn')
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-dashboard-orders-details', {
            template: '#v-dashboard-orders-details-template',

            data() {
                return {
                    activeTab: 'pending',

                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            watch: {
                activeTab: {
                    handler() {
                        this.$emitter.emit('order-tab-changed', this.activeTab);
                    },

                    deep: true
                }
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'recent-orders';

                    this.$axios.get("{{ route('seller.dashboard.stats') }}", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                }
            }
        });
    </script>

    <script type="module">
        app.component('v-order-table', {
            template: '#v-order-table-template',

            props: ['statistics'],

            data() {
                return {
                    columnNames: @json(trans('marketplace::app.seller.orders.index.datagrid')),

                    orders: [],
                }
            },

            mounted() {
                this.orders = this.statistics['pending'];

                this.$emitter.on('order-tab-changed', this.updateTable);
            },

            methods: {
                updateTable(currentTab) {
                    this.orders = this.statistics[currentTab];
                }
            }
        });
    </script>
@endPushOnce
