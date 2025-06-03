<!-- Top Selling Products Vue Component -->
<v-dashboard-top-products>
    <!-- Shimmer -->
    <x:marketplace::seller.shimmer.dashboard.top-selling-products />
</v-dashboard-top-products>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-top-products-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x:marketplace::seller.shimmer.dashboard.top-selling-products />
        </template>

        <!-- Total Products Section -->
        <template v-else>
            <div class="grid content-start gap-4 rounded-xl border border-[#E9E9E9] p-7">
                <div class="flex max-h-11 items-center justify-between">
                    <h3 class="py-2.5 flex-1 text-xl font-medium text-navyBlue">
                        @lang('marketplace::app.seller.dashboard.top-products')
                    </h3>

                    <div
                        class="secondary-button px-5 py-2.5"
                        v-if="report.statistics.length >= 5"
                    >
                        <a href="{{route('seller.products.index')}}">
                            @lang('marketplace::app.seller.dashboard.view-all-btn')
                        </a>
                    </div>
                </div>

                <template v-if="report.statistics.length">
                    {!! view_render_event('bagisto.seller.dashboard.top_products.list.before') !!}
                    
                    <div
                        class="grid grid-cols-2 border-b py-4 last:border-b-0"
                        v-for="orderItem in report.statistics"
                    >
                        <!-- Product Info -->
                        <div class="flex flex-col flex-1 gap-1">
                            <span class="text-sm font-medium">
                                @{{ orderItem.name }}
                            </span>

                            <span class="text-sm font-normal opacity-80">
                                @{{ orderItem.sku }}
                            </span>
                        </div>

                        <!-- Product Image and Price Info -->
                        <div class="flex gap-4">
                            <!-- Product Image -->
                            <div class="w-15 h-15">
                                <template v-if="!orderItem.item.product.images.length">
                                    <img
                                        class="h-full w-full rounded-xl object-cover"
                                        src="{{ bagisto_asset('images/small-product-placeholder.webp') }}"
                                        alt="Product Image"
                                    >
                                </template>

                                <template v-else>
                                    <img
                                        class="h-full w-full rounded-xl object-cover"
                                        :src="orderItem.item.product.images[0].url"
                                        alt="Product Image"
                                    >
                                </template>
                            </div>

                            <!-- Price Info -->
                            <div class="flex flex-col gap-1">
                                <span class="text-sm leading-[22px] font-medium">
                                    @{{ "@lang('marketplace::app.seller.dashboard.total-sale')".replace(':total', $shop.formatPrice(orderItem.revenue)) }}
                                </span>

                                <span class="text-sm leading-[22px] font-normal opacity-80">
                                    @{{ "@lang('marketplace::app.seller.dashboard.per-unit')".replace(':price', $shop.formatPrice(orderItem.price)) }}
                                </span>
                            </div>

                            <a
                                :href="`{{ route('seller.products.edit', '') }}/${orderItem.item.product.id}`"
                                class="icon-arrow-right place-self-center cursor-pointer rounded-md text-2xl transition-all hover:bg-gray-100"
                                title="View"
                            >
                            </a>
                        </div>
                    </div>
                    
                    {!! view_render_event('bagisto.seller.dashboard.top_products.list.after') !!}        
                </template>

                <template v-else>
                    <div class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10">
                        <img
                            src="{{ bagisto_asset('images/icon-add-product.svg', 'marketplace') }}"
                            class="h-20 w-20"
                        >
            
                        <div class="flex flex-col items-center">
                            <p class="text-base font-semibold text-[#6E6E6E]">
                                @lang('marketplace::app.seller.dashboard.add-product')
                            </p>
            
                            <p class="text-center text-[#6E6E6E]">
                                @lang('marketplace::app.seller.dashboard.product-info')
                            </p>
                        </div>
                    </div>            
                </template>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-top-products', {
            template: '#v-dashboard-top-products-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'top-products';

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
@endPushOnce
