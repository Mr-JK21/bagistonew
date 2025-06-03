@php
    $seller = seller()->user();
@endphp

<header class="sticky top-0 z-10 hidden items-center justify-between border-b bg-white px-7 py-4 lg:flex">
    <div class="flex items-center gap-1.5">
        <!-- Logo -->
        <a href="{{ route('seller.dashboard.index') }}">
            @if ($seller->logo)
                <img
                    class="h-10"
                    src="{{ Storage::url($seller->logo) }}"
                    alt="Seller Logo"
                />
            @else
                <img
                    src="{{ bagisto_asset('images/logo.svg') }}"
                    alt="Seller Logo"
                />
            @endif
        </a>

        <!-- Search Bar Component -->
        <x-marketplace::seller.layouts.header.search />
    </div>

    <div class="flex gap-7">
        <a 
            href="{{ route('shop.home.index') }}"
            target="_blank"
            class="flex"
        >
            <span 
                class="mp-home-icon cursor-pointer rounded-md p-1 text-2xl transition-all hover:bg-gray-100"
                title="@lang('marketplace::app.components.seller.layouts.header.home-page')"
            >
            </span>
        </a>

        <a 
            href="{{ route('shop.marketplace.sellers.show', $seller->shop_url)}}" 
            target="_blank"
            class="flex"
        >
            <span 
                class="mp-store-icon cursor-pointer rounded-md p-1 text-2xl transition-all hover:bg-gray-100"
                title="@lang('marketplace::app.components.seller.layouts.header.visit-shop')"
            >
            </span>
        </a>

        @php
            $unreadCount = $seller->communication?->seller_unread_count;
        @endphp
        
        <a
            class="flex items-baseline relative"
            href="{{ route('seller.communication.index') }}"
        >
            <span class="mp-notification-icon cursor-pointer rounded-md p-1 text-2xl transition-all hover:bg-gray-100">
            </span>

            @if ($unreadCount)
                <span
                    class="h-5 w-5 absolute flex items-center justify-center top-0 right-0 -mt-1 -mr-1 text-xs text-white bg-blue-500 rounded-full px-1"
                >
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </a>

        <!-- Seller profile -->
        <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <x-slot:toggle>
                <div class="flex items-baseline">
                    <span class="icon-users cursor-pointer rounded-md p-1 text-2xl transition-all hover:bg-gray-100">
                    </span>
                </div>
            </x-slot>

            <!-- Seller Dropdown -->
            <x-slot:content>
                <div class="grid gap-1">
                    <a
                        class="cursor-pointer rounded-xl px-5 py-2 text-base hover:bg-gray-100"
                        href="{{ route('seller.profile.index') }}"
                    >
                        @lang('marketplace::app.components.seller.layouts.header.my-profile')
                    </a>

                    <!--Seller logout-->
                    <x-shop::form
                        method="DELETE"
                        action="{{ route('seller.session.destroy') }}"
                        id="sellerLogout"
                    >
                    </x-shop::form>

                    <a
                        class="cursor-pointer rounded-xl px-5 py-2 text-base hover:bg-gray-100"
                        href="{{ route('admin.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('sellerLogout').submit();"
                    >
                        @lang('marketplace::app.components.seller.layouts.header.logout')
                    </a>
                </div>
            </x-slot>
        </x-shop::dropdown>
    </div>
</header>
