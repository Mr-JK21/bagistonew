<x-marketplace::seller.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.seller.dashboard.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="seller_dashboard" />
    @endSection
 
    <div class="flex flex-col gap-2.5">
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap max-sm:w-full">
            <p class="text-2xl font-medium">
                @lang('marketplace::app.seller.dashboard.hi-seller', ['seller_name' => $seller->name])
            </p>
            
            {!! view_render_event('bagisto.seller.dashboard.filters.before') !!}
            
            <!-- Filter Component -->
            <v-dashboard-filters>
                <x:marketplace::seller.shimmer.dashboard.filter />
            </v-dashboard-filters>
            
            {!! view_render_event('bagisto.seller.dashboard.filters.after') !!}
        </div>

        <p class="text-xs font-medium opacity-80">
            @lang('marketplace::app.seller.dashboard.hi-comment')
        </p>
    </div>

    {!! view_render_event('bagisto.seller.dashboard.profile_score.before') !!}

    @if (is_null($seller->parent_id))
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mx-auto mt-6 rounded-lg flex flex-wrap items-center justify-between">
            <!-- Text Content -->
            <div class="space-y-2 w-full sm:w-2/3">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">
                    @lang('marketplace::app.seller.dashboard.profile-score')
                </h2>
                
                <p class="text-sm sm:text-base text-gray-600">
                    @lang('marketplace::app.seller.dashboard.profile-score-desc')
                </p>
                
                @if ($seller->profile_score < 100)
                    <a
                        href="{{ route('seller.profile.index') }}"
                        class="inline-block text-sm font-medium text-blue-600 hover:underline"
                    >
                        @lang('marketplace::app.seller.dashboard.complete-profile-link')
                    </a>
                @endif
            </div>
        
            <!-- Profile Score Circle -->
            <div class="relative w-20 h-20 sm:w-24 sm:h-24 mt-4 sm:mt-0">
                <svg
                    class="w-full h-full transform -rotate-90"
                    viewBox="0 0 36 36"
                    aria-hidden="true"
                >
                    <!-- Background Circle -->
                    <circle
                        class="text-gray-300"
                        stroke-width="4"
                        stroke="currentColor"
                        fill="none"
                        r="16"
                        cx="18"
                        cy="18"
                    />
                    
                    <!-- Progress Circle -->
                    <circle
                        @class([
                            'text-red-500'    => $seller->profile_score < 25,
                            'text-orange-500' => $seller->profile_score >= 25 && $seller->profile_score < 50,
                            'text-yellow-500' => $seller->profile_score >= 50 && $seller->profile_score < 75,
                            'text-green-500'  => $seller->profile_score >= 75,
                        ])
                        stroke-width="4"
                        stroke-dasharray="100"
                        stroke-dashoffset="{{ 100 - $seller->profile_score }}"
                        stroke-linecap="round"
                        stroke="currentColor"
                        fill="none"
                        r="16"
                        cx="18"
                        cy="18"
                    />
                </svg>
        
                <!-- Profile Score Label -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-base sm:text-xl font-bold text-gray-800">
                        {{ $seller->profile_score }}%
                    </span>
                </div>
            </div>
        </div>
    @endif

    {!! view_render_event('bagisto.seller.dashboard.profile_score.after') !!}

    {!! view_render_event('bagisto.seller.dashboard.over_all_details.before') !!}
    
    <!-- Over All Details -->
    @include('marketplace::seller.dashboard.over-all-details')

    {!! view_render_event('bagisto.seller.dashboard.over_all_details.after') !!}

    {!! view_render_event('bagisto.seller.dashboard.total_sale_and_total_visitors.before') !!}

    <div class="mt-8 grid grid-cols-1 gap-8 md:grid-cols-2">
        {!! view_render_event('bagisto.seller.dashboard.total_sale.before') !!}

        <!-- Total Sale -->
        @include('marketplace::seller.dashboard.total-sales')

        {!! view_render_event('bagisto.seller.dashboard.total_sale.after') !!}

        {!! view_render_event('bagisto.seller.dashboard.total_visitors.before') !!}

        <!-- Total Visitors -->
        @include('marketplace::seller.dashboard.total-visitors')

        {!! view_render_event('bagisto.seller.dashboard.total_visitors.after') !!}
    </div>

    {!! view_render_event('bagisto.seller.dashboard.total_sale_and_total_visitors.after') !!}

    {!! view_render_event('bagisto.seller.dashboard.orders.before') !!}
    
    <!-- Orders Listing -->
    @include('marketplace::seller.dashboard.orders')

    {!! view_render_event('bagisto.seller.dashboard.orders.after') !!}

    {!! view_render_event('bagisto.seller.dashboard.stock_threshold_and_top_products.before') !!}

    <div class="mt-8 grid grid-cols-1 gap-8 md:grid-cols-2">
        {!! view_render_event('bagisto.seller.dashboard.stock_threshold.before') !!}

        <!-- Stock Threshold Products -->
        @include('marketplace::seller.dashboard.stock-threshold-products')

        {!! view_render_event('bagisto.seller.dashboard.stock_threshold.after') !!}

        {!! view_render_event('bagisto.seller.dashboard.top_products.before') !!}

        <!-- Top Products -->
        @include('marketplace::seller.dashboard.top-selling-products')

        {!! view_render_event('bagisto.seller.dashboard.top_products.after') !!}
    </div>

    {!! view_render_event('bagisto.seller.dashboard.stock_threshold_and_top_products.after') !!}

    {!! view_render_event('bagisto.seller.dashboard.top_customers_and_top_categories.before') !!}

    <div class="mt-8 grid grid-cols-1 gap-8 md:grid-cols-2">
        {!! view_render_event('bagisto.seller.dashboard.top_customers.before') !!}

        <!-- Top Customers -->
        @include('marketplace::seller.dashboard.top-customers')

        {!! view_render_event('bagisto.seller.dashboard.top_customers.after') !!}

        {!! view_render_event('bagisto.seller.dashboard.top_categories.before') !!}

        <!-- Top Categories -->
        @include('marketplace::seller.dashboard.top-categories')

        {!! view_render_event('bagisto.seller.dashboard.top_categories.after') !!}
    </div>

    {!! view_render_event('bagisto.seller.dashboard.top_customers_and_top_categories.after') !!}

    @pushOnce('scripts')
        <script
            type="module"
            src="{{ bagisto_asset('js/chart.js', 'admin') }}"
        >
        </script>

        <script
            type="text/x-template"
            id="v-dashboard-filters-template"
        >
            <div class="grid items-center gap-2.5 md:flex">
                <!-- Date Range -->
                <x-marketplace::seller.form.control-group class="!mb-0 w-full">
                    <x-marketplace::seller.form.control-group.control
                        type="select"
                        name="range"
                        class="!mb-0 md:w-36"
                        v-model="range"
                        @change="applyRangeFilter()"
                    >
                        <option value="">
                            @lang('marketplace::app.seller.dashboard.date-range')
                        </option>
                        
                        @foreach (['today', 'week', 'month', 'year'] as $type)
                            <option value="{{ $type }}">
                                @lang('marketplace::app.seller.dashboard.'.$type)
                            </option>
                        @endforeach
                    </x-marketplace::seller.form.control-group.control>
                </x-marketplace::seller.form.control-group>

                <div class="flex gap-x-2.5">
                    <!-- Start Date -->
                    <x-shop::flat-picker.date class="w-36">
                        <input
                            name="startDate"
                            class="h-11 w-36 rounded-lg border-2 border-[#E9E9E9] bg-white px-3 py-2.5 text-sm font-normal text-navyBlue transition-all hover:border-gray-400 focus:border-gray-400"
                            v-model="filters.start"
                        >
                    </x-shop::flat-picker.date>
        
                    <!-- End Date -->
                    <x-shop::flat-picker.date class="w-36">
                        <input
                            name="endDate"
                            class="h-11 w-36 rounded-lg border-2 border-[#E9E9E9] bg-white px-3 py-2.5 text-sm font-normal text-navyBlue transition-all hover:border-gray-400 focus:border-gray-400"
                            v-model="filters.end"
                        >
                    </x-shop::flat-picker.date>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-dashboard-filters', {
                template: '#v-dashboard-filters-template',

                data() {
                    return {
                        filters: {
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

                methods: {
                    applyRangeFilter() {
                        if (this.range == 'week') {
                            this.filters.start = "{{ now()->startOfWeek()->format('Y-m-d') }}";
                        } else if (this.range == 'month') {
                            this.filters.start = "{{ now()->startOfMonth()->format('Y-m-d') }}";
                        } else if (this.range == 'year') {
                            this.filters.start = "{{ now()->startOfYear()->format('Y-m-d') }}";
                        } else {
                            this.filters.start = "{{ now()->format('Y-m-d') }}";
                        }
                    }
                }
            });
        </script>
    @endPushOnce
</x-marketplace::seller.layouts>
