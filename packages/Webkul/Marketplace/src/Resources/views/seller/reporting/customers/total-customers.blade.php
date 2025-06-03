<!-- Total Customer Vue Component -->
<v-reporting-customers-total-customers>
    <!-- Shimmer -->
    <x-marketplace::seller.shimmer.reporting.customers.total-customers /> 
</v-reporting-customers-total-customers>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-customers-total-customers-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-marketplace::seller.shimmer.reporting.customers.total-customers />
        </template>

        <!-- Total Customer Section -->
        <template v-else>
            <div class="rounded-xl border bg-white py-6 px-[30px]">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="flex-1 text-xl font-medium text-navyBlue">
                        @lang('marketplace::app.seller.reporting.customers.index.total-customers')
                    </p>

                    <a
                        href="{{ route('seller.reporting.customers.view', ['type' => 'total-customers']) }}"
                        class="cursor-pointer text-base text-blue-600 transition-all hover:underline"
                    >
                        @lang('marketplace::app.seller.reporting.customers.index.view-details')
                    </a>
                </div>
                
                <!-- Content -->
                <div class="grid gap-4">
                    <div class="flex items-center gap-1">
                        <p class="text-xl font-medium">
                            @{{ report.statistics.customers.current }}
                        </p>
                        
                        <p
                            class="text-sm text-emerald-500"
                            :class="[report.statistics.customers.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                        >
                            @{{ report.statistics.customers.progress.toFixed(2) }}%
                        </p>
                    </div>

                    <p class="text-sm font-normal">
                        @lang('marketplace::app.seller.reporting.customers.index.customers-over-time')
                    </p>

                    <!-- Line Chart -->
                    <x-admin::charts.line
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
        app.component('v-reporting-customers-total-customers', {
            template: '#v-reporting-customers-total-customers-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            computed: {
                chartLabels() {
                    return this.report.statistics.over_time.current.map(({ label }) => label);
                },

                chartDatasets() {
                    return [{
                        data: this.report.statistics.over_time.current.map(({ total }) => total),
                        lineTension: 0.2,
                        pointStyle: false,
                        borderWidth: 2,
                        borderColor: '#0E9CFF',
                        backgroundColor: 'rgba(14, 156, 255, 0.3)',
                        fill: true,
                    }, {
                        data: this.report.statistics.over_time.previous.map(({ total }) => total),
                        lineTension: 0.2,
                        pointStyle: false,
                        borderWidth: 2,
                        borderColor: '#34D399',
                        backgroundColor: 'rgba(52, 211, 153, 0.3)',
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

                    filters.type = 'total-customers';

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