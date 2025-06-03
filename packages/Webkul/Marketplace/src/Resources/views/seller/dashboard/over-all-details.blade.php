<!-- Over Details Vue Component -->
<v-dashboard-overall-details>
    <!-- Shimmer -->
    <x:marketplace::seller.shimmer.dashboard.over-all-details />
</v-dashboard-overall-details>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-overall-details-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x:marketplace::seller.shimmer.dashboard.over-all-details />
        </template>

        <!-- Overall Details -->
        <template v-else>
            <div class="mt-8 grid gap-2.5 rounded-xl border border-[#E9E9E9] p-5">
                <h4 class="text-xl font-medium">
                    @lang('marketplace::app.seller.dashboard.overall-details')
                </h4>

                {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.before') !!}
                
                <div class="grid grid-cols-2 gap-2.5 md:grid-cols-3">
                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.total_sales.before') !!}
                    
                    <!-- Total Sales -->
                    <div class="py-2.5">
                        <div class="flex items-end gap-1">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.total_sales.formatted_total }}
                            </h3>

                            <p
                                class="text-sm font-normal"
                                :class="[report.statistics.total_sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.total_sales.progress.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm font-normal text-[#757575]">
                            @lang('marketplace::app.seller.dashboard.total-sales')
                        </p>
                    </div>

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.total_sales.after') !!}

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.total_payout.before') !!}
                    
                    <!-- Total Payout -->
                    <div class="py-2.5">
                        <div class="flex items-end gap-1">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.total_payout.formatted_total }}
                            </h3>

                            <p class="text-sm font-normal text-[#060C3B]">
                                @{{ Math.abs(report.statistics.total_payout.percent.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm font-normal text-[#757575]">
                            @lang('marketplace::app.seller.dashboard.total-payout')
                        </p>
                    </div>

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.total_payout.after') !!}

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.remaining_payout.before') !!}

                    <!-- Remaining Payout -->
                    <div class="py-2.5">
                        <div class="flex items-end gap-1">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.remaining_payout.formatted_total }}
                            </h3>

                            <p class="text-sm font-normal text-[#060C3B]">
                                @{{ Math.abs(report.statistics.remaining_payout.percent.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm font-normal text-[#757575]">
                            @lang('marketplace::app.seller.dashboard.remaining-payout')
                        </p>
                    </div>

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.remaining_payout.after') !!}

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.total_orders.before') !!}

                    <!-- Total Orders -->
                    <div class="py-2.5">
                        <div class="flex items-end gap-1">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.total_orders.current }}
                            </h3>

                            <p
                                class="text-sm font-normal"
                                :class="[report.statistics.total_orders.progress < 0 ? 'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.total_orders.progress.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm font-normal text-[#757575]">
                            @lang('marketplace::app.seller.dashboard.total-orders')
                        </p>
                    </div>

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.total_orders.after') !!}

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.total_customers.before') !!}

                    <!-- Total Customers -->
                    <div class="py-2.5">
                        <div class="flex items-end gap-1">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.total_customers.current }}
                            </h3>

                            <p
                                class="text-sm font-normal"
                                :class="[report.statistics.total_customers.progress < 0 ? 'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.total_customers.progress.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm font-normal text-[#757575]">
                            @lang('marketplace::app.seller.dashboard.total-customers')
                        </p>
                    </div>

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.total_customers.after') !!}

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.avg_sale.before') !!}
                    
                    <!-- Avg Sale -->
                    <div class="py-2.5">
                        <div class="flex items-end gap-1">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.avg_sales.formatted_total }}
                            </h3>

                            <p
                                class="text-sm font-normal"
                                :class="[report.statistics.avg_sales.progress < 0 ? 'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.avg_sales.progress.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm font-normal text-[#757575]">
                            @lang('marketplace::app.seller.dashboard.avg-order-sell')
                        </p>
                    </div>

                    {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.avg_sale.after') !!}
                </div>

                {!! view_render_event('bagisto.seller.dashboard.over_all_details.stats.after') !!}
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-overall-details', {
            template: '#v-dashboard-overall-details-template',

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

                    filters.type = 'over-all';

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
