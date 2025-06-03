@php
    $channel = core()->getCurrentChannel();
@endphp

@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <div class="container px-[60px] max-lg:px-8 max-sm:px-4">
        <div class="grid gap-16 md:mt-8">
            <!-- Banner -->
            <div class="flex items-center justify-between bg-[#E6E9EE] max-sm:flex-col">
                <div class="grid w-full gap-y-5 md:w-[480px] max-sm:mt-8 max-sm:px-4 ltr:md:ml-9 rtl:md:mr-9">
                    <h1 class="font-dmserif text-5xl font-normal leading-[68px] text-navyBlue">
                        {{ core()->getConfigData('marketplace.settings.landing_page.banner_title') }}
                    </h1>
    
                    <h2 class="text-base font-medium text-navyBlue">
                        {{ core()->getConfigData('marketplace.settings.landing_page.banner_description') }}
                    </h2>
                    
                    @if (auth()->guard('seller')->check())
                        <a
                            href="{{route('seller.dashboard.index')}}"
                            class="primary-button flex items-center gap-2.5"
                        >
                            @lang('marketplace::app.shop.marketplace.index.visit-shop')
                            <span class="icon-arrow-right-stylish cursor-pointer text-2xl"></span>
                        </a>
                    @else
                        <a
                            href="{{route('seller.register.create')}}"
                            class="primary-button flex items-center gap-2.5"
                        >
                            {{ core()->getConfigData('marketplace.settings.landing_page.banner_btn_title') }}
                            <span class="icon-arrow-right-stylish cursor-pointer text-2xl"></span>
                        </a>
                    @endif
                </div>

                <img
                    src="{{ Storage::url(core()->getConfigData('marketplace.settings.landing_page.banner_image')) }}"
                    class="mt-6 md:mr-14"
                    alt="marketplace banner"
                    width="556"
                    height="779"
                />
            </div>
    
            <!-- Banner Bottom Content -->
            <div class="flex flex-wrap justify-center gap-x-32 gap-y-5 max-sm:grid max-sm:grid-cols-1">
                <div class="flex flex-col items-center">
                    <p class="text-center font-dmserif text-5xl font-normal leading-[70px] text-navyBlue">
                        {{ core()->getConfigData('marketplace.settings.landing_page.community_count') }}+
                    </p>
            
                    <p class="text-base font-medium leading-6">
                        @lang('marketplace::app.shop.marketplace.index.seller-community')
                    </p>
                </div>
            
                <div class="flex flex-col items-center">
                    <p class="text-center font-dmserif text-5xl font-medium leading-[70px] text-navyBlue">
                        {{ core()->getConfigData('marketplace.settings.landing_page.business_hour') }}
                    </p>
            
                    <p class="text-base font-medium leading-6">
                        @lang('marketplace::app.shop.marketplace.index.online-business')
                    </p>
                </div>
            
                <div class="flex flex-col items-center">
                    <p class="text-center font-dmserif text-5xl font-medium leading-[70px] text-navyBlue">
                        {{ core()->getConfigData('marketplace.settings.landing_page.payment_duration') }}
                    </p>
            
                    <p class="text-base font-medium leading-6">
                        @lang('marketplace::app.shop.marketplace.index.days-payment')
                    </p>
                </div>
            
                <div class="flex flex-col items-center">
                    <p class="text-center font-dmserif text-5xl font-medium leading-[70px] text-navyBlue">
                        {{ core()->getConfigData('marketplace.settings.landing_page.serviceable_pincode') }}+
                    </p>
            
                    <p class="text-base font-medium leading-6">
                        @lang('marketplace::app.shop.marketplace.index.serviceable-pincode')
                    </p>
                </div>
            </div>
            
    
            <!-- Featured Section -->
            <div class="flex flex-col gap-6">
                <div class="grid max-w-[848px] md:gap-6">
                    <h2 class="text-2xl font-medium leading-10">
                        {{ core()->getConfigData('marketplace.settings.landing_page.feature_title') }}
                    </h2>
                    
                    <p class="mt-2.5 text-base font-normal leading-7">
                        {{ core()->getConfigData('marketplace.settings.landing_page.feature_description') }}
                    </p>
                </div>
        
                <div class="flex gap-6 max-sm:grid">
                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach(collect(['box1', 'box2', 'box3', 'box4']) as $item)
                            <div class="grid content-start gap-2 rounded-md border border-[#E9E9E9] p-4">
                                <div class="flex h-16 min-h-16 w-16 min-w-16 rounded-full bg-[#F1EADF] p-5">
                                    <img
                                        src="{{ Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_'.$item.'_icon')) }}"
                                        alt="vector icon"
                                        width="24"
                                        height="24"
                                    >
                                </div>
            
                                <h2 class="text-xl font-medium leading-10">
                                    {{ core()->getConfigData('marketplace.settings.landing_page.feature_'.$item.'_title') }}
                                </h2> 
            
                                <p class="mt-px text-base font-normal leading-7">
                                    {{ core()->getConfigData('marketplace.settings.landing_page.feature_'.$item.'_desc') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <x-shop::media.images.lazy
                        :src="Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_image'))"
                        class="max-w-full rounded-md"
                        alt="women holding flowers"
                        width="417"
                        height="608"
                    >
                    </x-shop::media.images.lazy>
                </div>
            </div>
        </div>
    
        <!-- Featured Sellers -->
        @if (core()->getConfigData('marketplace.settings.featured_sellers.status'))
            <v-featured-seller></v-featured-seller>
        @endif

        <div class="mt-20 grid gap-12 bg-[#F5F5F5] px-8 py-16 max-sm:mt-8 max-sm:px-4 max-sm:py-8">
            <!-- Title and Description Section -->
            <div class="text-center">
                <h2 class="text-3xl font-semibold leading-tight">
                    {{ core()->getConfigData('marketplace.settings.landing_page.journey_title') }}
                </h2>
            
                <p class="mt-4 text-lg font-normal leading-7 text-gray-600">
                    {{ core()->getConfigData('marketplace.settings.landing_page.journey_description') }}
                </p>
            </div>
        
            <!-- Steps Section -->
            <div class="grid gap-8 text-center sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                @foreach (collect(['step1', 'step2', 'step3', 'step4', 'step5']) as $key => $step)
                    <div class="flex flex-col items-center gap-4 p-6 rounded-lg bg-white shadow-md">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#F1EADF]">
                            <img
                                src="{{ Storage::url(core()->getConfigData('marketplace.settings.landing_page.journey_'.$step.'_icon')) }}"
                                alt="step icon"
                                class="h-10 w-10"
                            >
                        </div>
        
                        <p class="text-lg font-medium text-gray-700">
                            @lang('marketplace::app.shop.marketplace.index.step', ['count' => ++$key])
                        </p>
        
                        <h3 class="text-xl font-semibold text-navyBlue">
                            {{ core()->getConfigData('marketplace.settings.landing_page.journey_'.$step.'_title') }}
                        </h3>
        
                        <p class="text-base font-normal leading-6 text-gray-600">
                            {{ core()->getConfigData('marketplace.settings.landing_page.journey_'.$step.'_desc') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>        
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-featured-seller-template"
        >
            <div
                class="mt-10 md:mt-20"
                v-if="sellers.length"
            >
                <div class="grid gap-6">
                    <div class="relative max-w-[800px] max-1180:w-full max-1180:max-w-full">
                        <h2 class="text-2xl font-medium leading-10">
                            @lang('marketplace::app.shop.marketplace.index.featured-seller')
                        </h2>
                        
                        <p class="mt-2.5 text-base font-normal leading-7">
                            @lang('marketplace::app.shop.marketplace.index.start-selling')
                        </p> 
                    </div>
    
                    <div class="relative max-1180:w-full max-1180:max-w-full">
                        <div class="grid gap-6 max-sm:justify-items-center max-sm:gap-4 md:grid-cols-3">                            
                            <div
                                class="grid content-start gap-2 rounded-md border border-[#E9E9E9] p-4 max-sm:min-w-full"
                                v-for="seller in sellers"
                            >
                                <x-shop::media.images.lazy
                                    ::src="seller.logo_url ?? `{{ bagisto_asset('images/default-logo.webp', 'marketplace') }}`"
                                    class="max-h-20 min-h-20 min-w-20 max-w-20 rounded-xl border border-[#E9E9E9]"
                                    alt="marketplace banner"
                                    width="80"
                                    height="80"
                                >
                                </x-shop::media.images.lazy>

                                <a
                                    :href="`{{route('shop.marketplace.sellers.show', '')}}/${seller.shop_url}`"
                                    class="text-xl font-medium"
                                    v-text="seller.shop_title"
                                >
                                </a>

                                <p class="text-base font-medium text-[#6E6E6E]">
                                    @{{ seller.full_address }}
                                </p>

                                <div class="flex items-center gap-2.5">
                                    <x-marketplace::shop.products.star-rating
                                        ::value="seller.avg_rating"
                                        :is-editable=false
                                    />
                            
                                    <div class="flex items-center gap-2.5">
                                        <p class="text-sm text-[#6E6E6E]">
                                            @{{ "@lang('marketplace::app.shop.marketplace.index.reviews', ['count' => ':count'])".replace(':count', seller.total_reviews) }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex flex-wrap items-center gap-2.5"
                                    v-if="seller.allowed_categories.length > 0"
                                >
                                    <p class="text-base font-medium leading-6 text-[#6E6E6E]">
                                        @lang('marketplace::app.shop.marketplace.index.deals-in')
                                    </p>

                                    <div
                                        class="flex rounded-xl bg-[#F1EADF] px-2.5 py-0.5"
                                        v-for="(category, index) in seller.allowed_categories"
                                    >
                                        @{{category}}
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-featured-seller', {
                template: '#v-featured-seller-template',

                data() {
                    return {
                        sellers: {},
                    }
                },

                mounted() {
                    this.get();
                },
    
                methods: {
                    get() {
                        this.$axios.get("{{route('shop.marketplace.featured_sellers')}}")
                            .then((response) => {
                                this.sellers = response.data;
                            })
                            .catch(error => {
                            });
                    }
                }
            });
        </script>
    @endPushOnce
</x-shop::layouts>