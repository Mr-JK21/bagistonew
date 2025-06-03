<!-- Products with Most Visits Vue Component -->
<v-reporting-products-with-most-visits>
    <!-- Shimmer -->
    <x-marketplace::seller.shimmer.reporting.products.most-visits />
</v-reporting-products-with-most-visits>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-products-with-most-visits-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-marketplace::seller.shimmer.reporting.products.most-visits />
        </template>

        <!-- Products with Most Visits Section -->
        <template v-else>
            <div class="rounded-xl border bg-white py-5 px-[30px]">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="flex-1 text-xl font-medium text-navyBlue">
                        @lang('marketplace::app.seller.reporting.products.index.products-with-most-visits')
                    </p>

                    <a
                        href="{{ route('seller.reporting.products.view', ['type' => 'products-with-most-visits']) }}"
                        class="cursor-pointer text-base text-blue-600 transition-all hover:underline"
                    >
                        @lang('marketplace::app.seller.reporting.products.index.view-details')
                    </a>
                </div>
                
                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Products with Most Visits -->
                    <template v-if="report.statistics.length">
                        <!-- Customers -->
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="product in report.statistics"
                            >
                                <p>
                                    @{{ product.name }}
                                </p>

                                <div class="flex items-center gap-5">
                                    <div class="relative h-2 w-full bg-slate-100">
                                        <div
                                            class="absolute left-0 h-2 bg-emerald-500"
                                            :style="{ 'width': product.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm font-semibold text-gray-600">
                                        @{{ product.visits }}
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
                            <span class="h-3.5 w-3.5 rounded-md bg-emerald-400"></span>

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
        app.component('v-reporting-products-with-most-visits', {
            template: '#v-reporting-products-with-most-visits-template',

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

                    filters.type = 'products-with-most-visits';

                    this.$axios.get("{{ route('seller.reporting.products.stats') }}", {
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