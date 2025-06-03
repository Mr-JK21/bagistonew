<!-- Total Customer Vue Component -->
<v-reporting-customers-total-traffic>
    <!-- Shimmer -->
    <x-marketplace::seller.shimmer.reporting.customers.total-traffic /> 
</v-reporting-customers-total-traffic>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-customers-total-traffic-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-marketplace::seller.shimmer.reporting.customers.total-traffic /> 
        </template>

        <!-- Total Customer Section -->
        <template v-else>
            <div class="rounded-xl border bg-white py-6 px-[30px]">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="flex-1 text-xl font-medium text-navyBlue">
                        @lang('marketplace::app.seller.reporting.customers.index.customers-traffic')
                    </p>
                </div>
                
                <!-- Content -->
                <div class="grid gap-4">
                    <div class="flex justify-between gap-4">
                        <!-- Total Visitors -->
                        <div class="grid gap-1">
                            <div class="flex items-center gap-1">
                                <p class="text-xl font-medium leading-none text-gray-800">
                                    @{{ report.statistics.total.current }}
                                </p>

                                <p
                                    class="text-sm leading-none text-emerald-500"
                                    :class="[report.statistics.total.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ report.statistics.total.progress.toFixed(2) }}%
                                </p>
                            </div>

                            <p class="text-sm font-normal leading-none text-gray-500">
                                @lang('marketplace::app.seller.reporting.customers.index.total-visitors')
                            </p>
                        </div>

                        <!-- Unique Visitors -->
                        <div class="grid gap-1">
                            <div class="flex items-center gap-1">
                                <p class="text-xl font-medium text-gray-800">
                                    @{{ report.statistics.unique.current }}
                                </p>

                                <p
                                    class="text-sm text-emerald-500"
                                    :class="[report.statistics.unique.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ report.statistics.unique.progress.toFixed(2) }}%
                                </p>
                            </div>

                            <p class="text-sm font-normal leading-none text-gray-500">
                                @lang('marketplace::app.seller.reporting.customers.index.unique-visitors')
                            </p>
                        </div>
                    </div>

                    <p class="text-sm font-normal">
                        @lang('marketplace::app.seller.reporting.customers.index.traffic-over-week')
                    </p>

                    <!-- Bar Chart -->
                    <x-admin::charts.bar
                        ::labels="chartLabels"
                        ::datasets="chartDatasets"
                    />

                    <!-- Date Range -->
                    <div class="flex justify-center gap-5">
                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-emerald-400"></span>

                            <p class="text-xs">
                                @{{ report.date_range.previous }}
                            </p>
                        </div>

                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-sky-400"></span>

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
        app.component('v-reporting-customers-total-traffic', {
            template: '#v-reporting-customers-total-traffic-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            computed: {
                chartLabels() {
                    return this.report.statistics.over_time.previous['label'];
                },

                chartDatasets() {
                    return [{
                        data: this.report.statistics.over_time.previous['total'],
                        pointStyle: false,
                        backgroundColor: '#34D399',
                        fill: true,
                    }, {
                        data: this.report.statistics.over_time.current['total'],
                        pointStyle: false,
                        backgroundColor: '#0E9CFF',
                        fill: true,
                    }];
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

                    filters.type = 'customers-traffic';

                    this.$axios.get("{{ route('seller.reporting.customers.stats') }}", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },
            }
        });
    </script>
@endPushOnce