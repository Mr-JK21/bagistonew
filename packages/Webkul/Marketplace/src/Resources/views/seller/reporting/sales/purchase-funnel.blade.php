<!-- Purchase Funnel Vue Component -->
<v-reporting-sales-purchase-funnel>
    <!-- Shimmer -->
    <x-marketplace::seller.shimmer.reporting.sales.purchase-funnel />
</v-reporting-sales-purchase-funnel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-sales-purchase-funnel-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-marketplace::seller.shimmer.reporting.sales.purchase-funnel />
        </template>

        <!-- Purchase Funnel Section -->
        <template v-else>
            <div class="rounded-xl border bg-white py-5 px-[30px]">
                <!-- Header -->
                <p class="mb-4 flex-1 text-xl font-medium text-navyBlue">
                    @lang('marketplace::app.seller.reporting.sales.index.purchase-funnel')
                </p>
                
                <!-- Content -->
                <div class="grid grid-cols-4 gap-6">
                    <!-- Total Visits -->
                    <div class="flex flex-col gap-4">
                        <div class="grid gap-0.5">
                            <p class="text-xl font-medium leading-none text-gray-800">
                                @{{ report.statistics.visitors.total }}
                            </p>

                            <p class="text-sm font-normal leading-none text-gray-500">
                                @lang('marketplace::app.seller.reporting.sales.index.total-visits')
                            </p>
                        </div>

                        <div class="relative aspect-[0.5/1] w-full bg-slate-100 rounded-lg">
                            <div
                                class="absolute bottom-0 w-full bg-emerald-400 rounded-b-lg"
                                :class="{ 'rounded-t-lg': report.statistics.visitors.progress === 100 }"
                                :style="{ 'height': report.statistics.visitors.progress + '%' }"
                            ></div>
                        </div>

                        <p class="text-sm">
                            @lang('marketplace::app.seller.reporting.sales.index.total-visits-info')
                        </p>
                    </div>

                    <!-- Total Product Visits -->
                    <div class="flex flex-col gap-4">
                        <div class="grid gap-0.5">
                            <p class="text-xl font-medium leading-none text-gray-800">
                                @{{ report.statistics.product_visitors.total }}
                            </p>

                            <p class="text-sm font-normal leading-none text-gray-500">
                                @lang('marketplace::app.seller.reporting.sales.index.product-views')
                            </p>
                        </div>

                        <div class="relative aspect-[0.5/1] w-full bg-slate-100 rounded-lg">
                            <div
                                class="absolute bottom-0 w-full bg-emerald-400 rounded-b-lg"
                                :class="{ 'rounded-t-lg': report.statistics.product_visitors.progress === 100 }"
                                :style="{ 'height': (report.statistics.product_visitors.progress).toFixed(2) + '%' }"
                            ></div>
                        </div>

                        <p
                            class="text-sm"
                            v-html="'@lang('marketplace::app.seller.reporting.sales.index.product-views-info')'.replace(':progress', '<span class=\'text-emerald-400\'>' + report.statistics.product_visitors.progress + '%</span>')"
                        ></p>
                    </div>

                    <!-- Total Added To Cart -->
                    <div class="flex flex-col gap-4">
                        <div class="grid gap-0.5">
                            <p class="text-xl font-medium leading-none text-gray-800">
                                @{{ report.statistics.carts.total }}
                            </p>

                            <p class="text-sm font-normal leading-none text-gray-500">
                                @lang('marketplace::app.seller.reporting.sales.index.added-to-cart')
                            </p>
                        </div>

                        <div class="relative aspect-[0.5/1] w-full bg-slate-100 rounded-lg">
                            <div
                                class="absolute bottom-0 w-full bg-emerald-400 rounded-b-lg"
                                :class="{ 'rounded-t-lg': report.statistics.carts.progress === 100 }"
                                :style="{ 'height': (report.statistics.carts.progress).toFixed(2) + '%' }"
                            ></div>
                        </div>

                        <p
                            class="text-sm"
                            v-html="'@lang('marketplace::app.seller.reporting.sales.index.added-to-cart-info')'.replace(':progress', '<span class=\'text-emerald-400\'>' + report.statistics.carts.progress + '%</span>')"
                        ></p>
                    </div>

                    <!-- Total Purchased -->
                    <div class="flex flex-col gap-4">
                        <div class="grid gap-0.5">
                            <p class="text-xl font-medium leading-none text-gray-800">
                                @{{ report.statistics.orders.total }}
                            </p>

                            <p class="text-sm font-normal leading-none text-gray-500">
                                @lang('marketplace::app.seller.reporting.sales.index.purchased')
                            </p>
                        </div>

                        <div class="relative aspect-[0.5/1] w-full bg-slate-100 rounded-lg">
                            <div
                                class="absolute bottom-0 w-full bg-emerald-400 rounded-b-lg"
                                :class="{ 'rounded-t-lg': report.statistics.orders.progress === 100 }"
                                :style="{ 'height': report.statistics.orders.progress + '%' }"
                            ></div>
                        </div>

                        <p
                            class="text-sm"
                            v-html="'@lang('marketplace::app.seller.reporting.sales.index.purchased-info')'.replace(':progress', '<span class=\'text-emerald-400\'>' + report.statistics.orders.progress + '%</span>')"
                        ></p>
                    </div>
                </div>

                <!-- Date Range Section -->
                <div class="mt-6 flex justify-center gap-5">
                    <div class="flex items-center gap-1">
                        <span class="h-3.5 w-3.5 rounded-md bg-emerald-400"></span>

                        <p class="text-xs">
                            @{{ report.date_range.current }}
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-reporting-sales-purchase-funnel', {
            template: '#v-reporting-sales-purchase-funnel-template',

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

                    filters.type = 'purchase-funnel';

                    this.$axios.get("{{ route('seller.reporting.sales.stats') }}", {
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