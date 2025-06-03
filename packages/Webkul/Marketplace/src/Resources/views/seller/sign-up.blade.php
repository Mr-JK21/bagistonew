<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('marketplace::app.seller.signup.page-title')"/>

    <meta name="keywords" content="@lang('marketplace::app.seller.signup.page-title')"/>
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.seller.signup.page-title')
    </x-slot>

	<div class="container mt-20 max-1180:px-5">
        {!! view_render_event('bagisto.seller.sign_up.logo.before') !!}
        
        <!-- Company Logo -->
        <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9">
            <a
                href="{{ route('shop.home.index') }}"
                class="m-[0_auto_20px_auto]"
                aria-label="@lang('marketplace::app.seller.signup.bagisto')"
            >
                <img
                    src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="131"
                    height="29"
                >
            </a>
        </div>

        {!! view_render_event('bagisto.seller.sign_up.logo.after') !!}
        
        <!-- Form Container -->
		<div
			class="m-auto w-full max-w-[870px] rounded-xl border border-[#E9E9E9] p-16 px-[90px] max-md:px-8 max-md:py-8"
        >
			<h1 class="font-dmserif text-4xl max-sm:text-2xl">
                @lang('marketplace::app.seller.signup.page-title')
            </h1>

			<p class="mt-4 text-xl text-[#6E6E6E] max-sm:text-base">
                @lang('marketplace::app.seller.signup.form-signup-text')
            </p>

            {!! view_render_event('bagisto.seller.sign_up.before') !!}
            
            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('seller.register.store')">
                    {!! view_render_event('bagisto.seller.sign_up.form_controls.before') !!}

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.name.before') !!}
                    
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.seller.signup.name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="rounded-lg !p-[20px_25px]"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('marketplace::app.seller.signup.name')"
                            :placeholder="trans('marketplace::app.seller.signup.name')"
                            aria-label="@lang('marketplace::app.seller.signup.name')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.name.after') !!}

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.url.before') !!}

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.seller.signup.url')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="rounded-lg !p-[20px_25px]"
                            name="url"
                            rules="required"
                            :value="old('url')"
                            :label="trans('marketplace::app.seller.signup.url')"
                            :placeholder="trans('marketplace::app.seller.signup.url')"
                            :aria-label="trans('marketplace::app.seller.signup.url')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="url" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.url.after') !!}

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.email.before') !!}
                    
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.seller.signup.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="rounded-lg !p-[20px_25px]"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('marketplace::app.seller.signup.email')"
                            placeholder="email@example.com"
                            aria-label="@lang('marketplace::app.seller.signup.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.email.after') !!}

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.password.before') !!}

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label class="required">
                            @lang('marketplace::app.seller.signup.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="rounded-lg !p-[20px_25px]"
                            name="password"
                            rules="required|min:6"
                            :value="old('password')"
                            :label="trans('marketplace::app.seller.signup.password')"
                            :placeholder="trans('marketplace::app.seller.signup.password')"
                            ref="password"
                            aria-label="@lang('marketplace::app.seller.signup.password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.password.after') !!}

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.password_confirmation.before') !!}
                    
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('marketplace::app.seller.signup.confirm-pass')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="rounded-lg !p-[20px_25px]"
                            name="password_confirmation"
                            rules="confirmed:@password"
                            value=""
                            :label="trans('marketplace::app.seller.signup.password')"
                            :placeholder="trans('marketplace::app.seller.signup.confirm-pass')"
                            aria-label="@lang('marketplace::app.seller.signup.confirm-pass')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password_confirmation" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.password_confirmation.after') !!}

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.captcha.before') !!}

                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <div class="mb-5 flex">
                            {!! Captcha::render() !!}
                        </div>
                    @endif

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.captcha.after') !!}

                    <div class="mt-8 flex">
                        <button
                            class="primary-button m-0 block w-full max-w-[374px] rounded-2xl px-11 py-4 text-center text-base ltr:ml-0 rtl:mr-0"
                            type="submit"
                        >
                            @lang('marketplace::app.seller.signup.button-title')
                        </button>
                    </div>

                    {!! view_render_event('bagisto.seller.sign_up.form_controls.after') !!}
                </x-shop::form>
            </div>

            {!! view_render_event('bagisto.seller.sign_up.after') !!}

			<p class="mt-5 font-medium text-[#6E6E6E]">
                @lang('marketplace::app.seller.signup.account-exists')

                <a class="text-navyBlue"
                    href="{{ route('seller.session.index') }}"
                >
                    @lang('marketplace::app.seller.signup.sign-in-button')
                </a>

                {!! view_render_event('bagisto.seller.sign_up.sign_in_btn.after') !!}
            </p>

            {!! view_render_event('bagisto.seller.sign_up.sign_in_btn.paragraph.after') !!}
		</div>

        {!! view_render_event('bagisto.seller.sign_up.form_container.after') !!}

        <p class="mb-4 mt-8 text-center text-xs text-[#6E6E6E]">
            @lang('marketplace::app.seller.signup.footer', ['current_year' => date('Y') ])
        </p>

        {!! view_render_event('bagisto.seller.sign_up.footer.after') !!}
	</div>

    @push('scripts')
        {!! Captcha::renderJS() !!}
    @endpush
</x-shop::layouts>