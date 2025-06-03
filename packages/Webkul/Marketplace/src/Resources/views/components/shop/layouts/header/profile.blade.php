@if (seller()->user()?->is_profile_completed)
    <a 
        class="px-5 py-2 text-sm cursor-pointer"
        href="{{ route('seller.dashboard.index') }}"
    >
        <div class="flex gap-2.5 items-center p-2.5 border rounded-lg">
            <img
                src="{{ seller()->user()->logo_url ?: bagisto_asset('images/default-logo.webp', 'marketplace') }}"
                class="max-h-[50px] min-h-[50px] min-w-[50px] max-w-[50px] rounded-xl"
                alt="@lang('marketplace::app.shop.layout.header.seller-logo')"
                width="50" 
                height="50"
            >

            <div class="flex flex-col">
                <p class="text-base font-normal">
                    {{ seller()->user()->shop_title }}
                </p>
                
                <p class="text-xs font-normal">
                    @lang('marketplace::app.shop.layout.header.switch-to-store')
                </p>
            </div>
        </div>
    </a>
@endif
