<!-- Customers with Most Orders Vue Component -->
<v-reporting-customers-with-most-orders>
    <!-- Shimmer -->
    <x-marketplace::seller.shimmer.reporting.customers.most-orders />
</v-reporting-customers-with-most-orders>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-customers-with-most-orders-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-marketplace::seller.shimmer.reporting.customers.most-orders />
        </template>

        <!-- Customers with Most Orders Section -->
        <template v-else>
            <div class="rounded-xl border bg-white py-5 px-[30px]">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="flex-1 text-xl font-medium text-navyBlue">
                        @lang('marketplace::app.seller.reporting.customers.index.customers-with-most-orders')
                    </p>

                    <a
                        href="{{ route('seller.reporting.customers.view', ['type' => 'customers-with-most-orders']) }}"
                        class="cursor-pointer text-base text-blue-600 transition-all hover:underline"
                    >
                        @lang('marketplace::app.seller.reporting.customers.index.view-details')
                    </a>
                </div>
                
                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Customers with Most Orders -->
                    <template v-if="report.statistics.length">
                        <!-- Customers -->
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="customer in report.statistics"
                            >
                                <p>
                                    @{{ customer.full_name }}
                                </p>

                                <div class="flex items-center gap-5">
                                    <div class="relative h-2 w-full bg-slate-100">
                                        <div
                                            class="absolute left-0 h-2 bg-emerald-500"
                                            :style="{ 'width': customer.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm font-semibold text-gray-600">
                                        @{{ customer.orders }}
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
        app.component('v-reporting-customers-with-most-orders', {
            template: '#v-reporting-customers-with-most-orders-template',

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

                    filters.type = 'customers-with-most-orders';

                    this.$axios.get("{{ route('seller.reporting.customers.stats') }}", {
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