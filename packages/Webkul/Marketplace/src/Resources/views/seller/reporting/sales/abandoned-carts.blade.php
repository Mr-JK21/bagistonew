<!-- Abandoned Carts Vue Component -->
<v-reporting-sales-abandoned-carts>
    <!-- Shimmer -->
    <x-marketplace::seller.shimmer.reporting.sales.abandoned-carts />
</v-reporting-sales-abandoned-carts>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-sales-abandoned-carts-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-marketplace::seller.shimmer.reporting.sales.abandoned-carts />
        </template>

        <!-- Abandoned Carts Section -->
        <template v-else>
            <div class="rounded-xl border bg-white py-5 px-[30px]">
                <div class="mb-4 flex items-center justify-between">
                    <p class="flex-1 text-xl font-medium text-navyBlue">
                        @lang('marketplace::app.seller.reporting.sales.index.abandoned-carts')
                    </p>

                    <a
                        href="{{ route('seller.reporting.sales.view', ['type' => 'abandoned-carts']) }}"
                        class="cursor-pointer text-base text-blue-600 transition-all hover:underline"
                    >
                        @lang('marketplace::app.seller.reporting.sales.index.view-details')
                    </a>
                </div>

                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Stats -->
                    <div class="flex justify-between gap-4">
                        <!-- Abandoned Revenue -->
                        <div class="grid gap-1">
                            <div class="flex items-center gap-1">
                                <p class="text-xl font-medium leading-none text-gray-800">
                                    @{{ report.statistics.sales.formatted_total }}
                                </p>
                                
                                <p
                                    class="text-sm leading-none text-emerald-500"
                                    :class="[report.statistics.sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.sales.progress.toFixed(2)) }}%
                                </p>
                            </div>

                            <p class="text-sm font-normal leading-none text-gray-500">
                                @lang('marketplace::app.seller.reporting.sales.index.abandoned-revenue')
                            </p>
                        </div>

                        <!-- Abandoned Cart -->
                        <div class="grid gap-1">
                            <div class="flex items-center gap-1">
                                <p class="text-xl font-medium leading-none text-gray-800">
                                    @{{ report.statistics.carts.current }}
                                </p>
                                
                                <p
                                    class="text-sm leading-none text-emerald-500"
                                    :class="[report.statistics.carts.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.carts.progress.toFixed(2)) }}%
                                </p>
                            </div>

                            <p class="text-sm font-normal leading-none text-gray-500">
                                @lang('marketplace::app.seller.reporting.sales.index.abandoned-carts')
                            </p>
                        </div>

                        <!-- Abandoned Rate -->
                        <div class="grid gap-1">
                            <div class="flex items-center gap-1">
                                <p class="text-xl font-medium leading-none text-gray-800">
                                    @{{ report.statistics.rate.current.toFixed(2) }}%
                                </p>

                                <p
                                    class="text-sm leading-none text-emerald-500"
                                    :class="[report.statistics.rate.progress >= 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.rate.current.toFixed(2)) }}%
                                </p>
                            </div>

                            <p class="text-sm font-normal leading-none text-gray-500">
                                @lang('marketplace::app.seller.reporting.sales.index.abandoned-rate')
                            </p>
                        </div>
                    </div>

                    <!-- Header -->
                    <p class="mt-4 py-2.5 text-base font-normal">
                        @lang('marketplace::app.seller.reporting.sales.index.abandoned-products')
                    </p>

                    <!-- Abandoned Products -->
                    <template v-if="report.statistics.products.length">
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="product in report.statistics.products"
                            >
                                <p>
                                    @{{ product.name }}
                                </p>

                                <div class="flex items-center gap-5">
                                    <div class="relative h-2 w-full bg-slate-100">
                                        <div
                                            class="absolute left-0 h-2 bg-blue-500"
                                            :style="{ 'width': product.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm font-semibold text-gray-600">
                                        @{{ product.count }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template v-else>
                        @include('marketplace::seller.reporting.empty')
                    </template>

                    <!-- Date Range -->
                    <div class="flex justify-center gap-5">
                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-blue-500"></span>

                            <p class="text-xs">
                                @{{ report.date_range.current }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-reporting-sales-abandoned-carts', {
            template: '#v-reporting-sales-abandoned-carts-template',

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

                    filters.type = 'abandoned-carts';

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