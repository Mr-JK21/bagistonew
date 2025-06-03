@if (core()->getConfigData('marketplace.settings.general.status'))
    <a
        href="{{ route('shop.marketplace.index') }}"
        aria-label="Sell"
    >
        <span class="mp-store-icon inline-block cursor-pointer text-[24px]"></span>
    </a>
@endif
