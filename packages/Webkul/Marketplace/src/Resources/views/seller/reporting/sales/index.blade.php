<x-marketplace::seller.layouts>
    <x-slot:title>
        @lang('marketplace::app.seller.reporting.sales.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="reporting.sales" />
    @endSection

    <!-- Page Header -->
    <div class="mb-7 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <!-- Title -->
        <div class="flex gap-1.5">
            <p class="text-2xl font-medium">
                @lang('marketplace::app.seller.reporting.sales.index.title')
            </p>
        </div>

        <!-- Actions -->
        <v-reporting-filters>
            <!-- Shimmer -->
            <div class="flex gap-2.5 items-center">
                @if (core()->getAllChannels()->count() > 1)
                    <div class="shimmer h-11 w-36 rounded-lg"></div>
                @endif
                
                <div class="shimmer h-11 w-36 rounded-lg"></div>
                <div class="shimmer h-11 w-36 rounded-lg"></div>
            </div>
        </v-reporting-filters>
    </div>

    <!-- Sales Stats Vue Component -->
    <div class="flex flex-1 flex-col gap-[30px] max-xl:flex-auto">
        <!-- Sales Section -->
        @include('marketplace::seller.reporting.sales.total-sales')

        <!-- Total Commission -->
        @include('marketplace::seller.reporting.sales.total-commission')

        <!-- Purchase Funnel and Abandoned Carts Sections Container -->
        <div class="flex justify-between gap-[30px] flex-1 [&>*]:flex-1 flex-wrap max-xl:flex-auto">
            <!-- Purchase Funnel Section -->
            @include('marketplace::seller.reporting.sales.purchase-funnel')

            <!-- Abandoned Carts Section -->
            @include('marketplace::seller.reporting.sales.abandoned-carts')
        </div>

        <!-- Total Orders and Average Order Value Sections Container -->
        <div class="flex justify-between gap-[30px] flex-1 [&>*]:flex-1 flex-wrap max-xl:flex-auto">
            <!-- Total Orders Section -->
            @include('marketplace::seller.reporting.sales.total-orders')

            <!-- Average Order Value Section -->
            @include('marketplace::seller.reporting.sales.average-order-value')
        </div>

        <!-- Tax Collected Sections Container -->
        @include('marketplace::seller.reporting.sales.tax-collected')

        <!-- Refunds and Top Payment Methods Sections Container -->
        <div class="flex justify-between gap-[30px] flex-1 [&>*]:flex-1 flex-wrap max-xl:flex-auto">
            <!-- Refunds Section -->
            @include('marketplace::seller.reporting.sales.total-refunds')

            <!-- Top Payment Methods Section -->
            @include('marketplace::seller.reporting.sales.top-payment-methods')
        </div>
    </div>

    @pushOnce('scripts')
        <script
            type="module"
            src="{{ bagisto_asset('js/chart.js', 'admin') }}"
        >
        </script>

        <script
            type="text/x-template"
            id="v-reporting-filters-template"
        >
            <div class="grid items-center gap-2.5 md:flex">
                <!--Channel -->
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

                <div class="flex gap-x-2.5">
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
        </script>

        <script type="module">
            app.component('v-reporting-filters', {
                template: '#v-reporting-filters-template',

                data() {
                    return {
                        channels: [
                            {
                                name: "@lang('admin::app.reporting.sales.index.all-channels')",
                                code: ''
                            },
                            ...@json(core()->getAllChannels()),
                        ],
                        
                        filters: {
                            channel: '',

                            start: "{{ $startDate->format('Y-m-d') }}",
                            
                            end: "{{ $endDate->format('Y-m-d') }}",
                        }
                    }
                },

                watch: {
                    filters: {
                        handler() {
                            this.$emitter.emit('reporting-filter-updated', this.filters);
                        },

                        deep: true
                    }
                },
            });
        </script>
    @endPushOnce
</x-marketplace::seller.layouts>
