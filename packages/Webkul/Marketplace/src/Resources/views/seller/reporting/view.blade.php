<x-marketplace::seller.layouts>
    <x-slot:title>
        @lang('marketplace::app.seller.reporting.' . $entity . '.index.' . request()->query('type'))
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="reporting.sales" />
    @endSection

    <v-reporting-stats-table>
        <!-- Shimmer -->
        <x-marketplace::seller.shimmer.reporting.view />
    </v-reporting-stats-table>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-reporting-stats-table-template"
        >
            <div>
                <!-- Page Header -->
                <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
                    <!-- Title -->
                    <div class="grid gap-1.5">
                        <p class="text-2xl font-medium">
                            @lang('marketplace::app.seller.reporting.' . $entity . '.index.' . request()->query('type'))
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2.5">
                        <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                            <x-slot:toggle>
                                <div class="flex items-baseline">
                                    <span class="mp-export-icon cursor-pointer rounded-md p-1 text-2xl transition-all hover:bg-gray-100">
                                    </span>
                                </div>
                            </x-slot>

                            <!-- Seller Dropdown -->
                            <x-slot:content class="rounded-[20px] border !p-2.5 shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)]">
                                <div class="grid gap-1">
                                    <span
                                        class="cursor-pointer rounded-xl px-5 py-2 text-base hover:bg-gray-100"
                                        @click="exportReporting('csv')"
                                    >
                                        @lang('marketplace::app.seller.reporting.view.export-csv')
                                    </span>
                                    
                                    <span
                                        class="cursor-pointer rounded-xl px-5 py-2 text-base hover:bg-gray-100"
                                        @click="exportReporting('xls')"
                                    >
                                        @lang('marketplace::app.seller.reporting.view.export-xls')
                                    </span>
                                </div>
                            </x-slot:content>
                        </x-shop::dropdown>
                       
                        <x-marketplace::seller.form.control-group.control
                            v-if="channels.length > 2"
                            type="select"
                            class="!mb-0 md:w-36"
                            v-model="filters.channel"
                        >                        
                            <option
                                v-for="(channel, index) in channels"
                                :key="index"
                                :value="channel.code"
                            >
                                @{{ channel.name }}
                            </option>
                        </x-marketplace::seller.form.control-group.control>

                        @if (in_array(request()->query('type'), [
                            'total-sales',
                            'total-orders',
                            'average-sales',
                            'tax-collected',
                            'refunds',
                            'total-customers',
                            'total-sold-quantities',
                            'total-products-added-to-wishlist',
                        ]))
                            <x-marketplace::seller.form.control-group.control
                                type="select"
                                class="!mb-0 md:w-36"
                                v-model="filters.period"
                            >                        
                                <option value="day">
                                    @lang('marketplace::app.seller.reporting.view.day')
                                </option>

                                <option value="month">
                                    @lang('marketplace::app.seller.reporting.view.month')
                                </option>

                                <option value="year">
                                    @lang('marketplace::app.seller.reporting.view.year')
                                </option>
                            </x-marketplace::seller.form.control-group.control>
                        @endif
            
                        <!-- Start Date -->
                        <x-shop::flat-picker.date class="w-36">
                            <input
                                class="h-11 w-36 rounded-lg border-2 border-[#E9E9E9] bg-white px-3 py-2.5 text-sm font-normal text-navyBlue transition-all hover:border-gray-400 focus:border-gray-400"
                                v-model="filters.start"
                            >
                        </x-shop::flat-picker.date>
            
                        <!-- End Date -->
                        <x-shop::flat-picker.date class="w-36">
                            <input
                                class="h-11 w-36 rounded-lg border-2 border-[#E9E9E9] bg-white px-3 py-2.5 text-sm font-normal text-navyBlue transition-all hover:border-gray-400 focus:border-gray-400"
                                v-model="filters.end"
                            >
                        </x-shop::flat-picker.date>
                    </div>
                </div>

                <div class="table-responsive grid w-full overflow-hidden rounded bg-white border">
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.head />

                        <x-shop::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <!-- Table Header -->
                        <div
                            class="row grid grid-cols-4 grid-rows-1 items-center gap-2.5 border-b bg-gray-50 px-4 py-2.5 font-semibold text-gray-600"
                            :style="`grid-template-columns: repeat(${reporing.statistics.columns.length}, minmax(0, 1fr))`"
                        >
                            <div
                                class="flex cursor-pointer gap-2.5"
                                v-for="column in reporing.statistics.columns"
                            >
                                <p class="text-gray-600">
                                    @{{ column.label }}
                                </p>
                            </div>
                        </div>

                        <!-- Table Body -->
                        <div
                            class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50"
                            style="grid-template-columns: repeat(4, minmax(0, 1fr));"
                            :style="`grid-template-columns: repeat(${reporing.statistics.columns.length}, minmax(0, 1fr))`"
                            v-if="reporing.statistics.records.length"
                            v-for="record in reporing.statistics.records"
                        >
                            <p v-for="column in reporing.statistics.columns">
                                @{{ record[column.key] }}
                            </p>
                        </div>

                        <div
                            v-else
                            class="row grid gap-2.5 border-b px-4 py-4 text-center text-gray-600 transition-all hover:bg-gray-50"
                        >
                            <p>@lang('marketplace::app.seller.reporting.view.not-available')</p>
                        </div>
                    </template>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-reporting-stats-table', {
                template: '#v-reporting-stats-table-template',

                data() {
                    return {
                        channels: [
                            {
                                name: "@lang('marketplace::app.seller.reporting.view.all-channels')",
                                code: ''
                            },
                            ...@json(core()->getAllChannels()),
                        ],
                        
                        filters: {
                            type: "{{ request()->query('type') }}",
                            
                            period: 'day',

                            channel: '',

                            start: "{{ $startDate->format('Y-m-d') }}",

                            end: "{{ $endDate->format('Y-m-d') }}",
                        },

                        reporing: [],

                        isLoading: true,
                    }
                },

                mounted() {
                    this.getStats();
                },

                watch: {
                    filters: {
                        handler() {
                            this.getStats();
                        },

                        deep: true
                    }
                },

                methods: {
                    getStats() {
                        this.isLoading = true;

                        this.$axios.get("{{ route('seller.reporting.' . $entity . '.view.stats') }}", {
                                params: this.filters
                            })
                            .then(response => {
                                this.reporing = response.data;

                                this.isLoading = false;
                            })
                            .catch(error => {});
                    },

                    exportReporting(format) {
                        let filters = this.filters;

                        filters.format = format;

                        window.open(
                            "{{ route('seller.reporting.' . $entity . '.export') }}?"  + new URLSearchParams(filters).toString(),
                            '_blank'
                        );
                    }
                }
            });
        </script>
    @endPushOnce
</x-marketplace::seller.layouts>
