@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @if ($toSeller)
                @lang('marketplace::app.emails.dear-seller'), 👋
            @else
                @lang('marketplace::app.emails.dear-admin'), 👋
            @endif
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('marketplace::app.emails.report-product.greeting')
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        @if ($toSeller)
            @lang('marketplace::app.emails.report-product.seller-msg', [
                'name'         => $flag->customer->name,
                'product_name' => $flag->product->product->name
            ]), 👋
        @else
            @lang('marketplace::app.emails.report-product.admin-msg', [
                'name'         => $flag->customer->name,
                'seller_name'  => $flag->seller->name,
                'product_name' => $flag->product->product->name
            ]), 👋
        @endif
    </p>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        @lang('marketplace::app.emails.report-product.reason', ['reason' => $flag->reason])
    </p>
@endcomponent