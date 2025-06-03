<x-marketplace::seller.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.seller.settings.general.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="general" />
    @endSection

    {!! view_render_event('marketplace.seller.settings.general.index.before') !!}

    <x-marketplace::seller.form
        method="PUT"
        :action="route('seller.settings.general.update')"
    >
        {!! view_render_event('bagisto.seller.settings.general.index.form_controls.before') !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-2xl font-medium">
                @lang('marketplace::app.seller.settings.general.index.title')
            </p>

            {!! view_render_event('bagisto.seller.settings.general.index.form_controls.buttons.before') !!}

            <div class="flex items-center gap-x-2.5">
                {!! view_render_event('bagisto.seller.settings.general.index.form_controls.buttons.save_button.before') !!}

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button px-5 py-2.5"
                >
                    @lang('marketplace::app.seller.settings.general.index.save-btn')
                </button>

                {!! view_render_event('bagisto.seller.settings.general.index.form_controls.buttons.save_button.after') !!}
            </div>

            {!! view_render_event('bagisto.seller.settings.general.index.form_controls.buttons.after') !!}
        </div>

        <!-- body content -->
        <div class="mt-3.5 flex gap-6 max-xl:flex-wrap">
            <!-- Left sub-component -->
            <div class="flex flex-1 flex-col gap-6 max-xl:flex-auto">
                <!-- Password Setting -->
                {!! view_render_event('bagisto.seller.settings.general.password_setting.before') !!}

                <div class="rounded-xl border border-[#E9E9E9] bg-white p-5">
                    <h3 class="mb-6 text-xl font-medium leading-8 text-navyBlue">
                        @lang('marketplace::app.seller.settings.general.index.password-setting.title')
                    </h3>
                    
                    {!! view_render_event('bagisto.seller.settings.password_setting.current_password.before') !!}
                    
                    <!-- Password -->
                    <x-marketplace::seller.form.control-group class="relative">
                        <x-marketplace::seller.form.control-group.label>
                            @lang('marketplace::app.seller.settings.general.index.password-setting.current-password')
                        </x-marketplace::seller.form.control-group.label>

                        <x-marketplace::seller.form.control-group.control
                            type="password"
                            name="current_password"
                            id="current_password"
                            :label="trans('marketplace::app.seller.settings.general.index.password-setting.current-password')"
                            :placeholder="trans('marketplace::app.seller.settings.general.index.password-setting.current-password')"
                        />

                        <span 
                            class="mp-eye-icon absolute bottom-3 cursor-pointer text-2xl ltr:right-2 rtl:left-2"
                            onclick="switchVisibility('current_password', this)"
                            role="presentation"
                            tabindex="0"
                        >

                        <x-marketplace::seller.form.control-group.error control-name="current_password" />
                    </x-marketplace::seller.form.control-group>

                    {!! view_render_event('bagisto.seller.settings.general.password_setting.current_password.after') !!}
                    
                    {!! view_render_event('bagisto.seller.settings.general.password_setting.new_password.before') !!}

                    <!-- New Password -->
                    <x-marketplace::seller.form.control-group class="relative">
                        <x-marketplace::seller.form.control-group.label>
                            @lang('marketplace::app.seller.settings.general.index.password-setting.new-password')
                        </x-marketplace::seller.form.control-group.label>

                        <x-marketplace::seller.form.control-group.control
                            type="password"
                            name="new_password"
                            id="new_password"
                            :label="trans('marketplace::app.seller.settings.general.index.password-setting.new-password')"
                            :placeholder="trans('marketplace::app.seller.settings.general.index.password-setting.new-password')"
                        />

                        <span 
                            class="mp-eye-icon absolute bottom-3 cursor-pointer text-2xl ltr:right-2 rtl:left-2"
                            onclick="switchVisibility('new_password', this)"
                            role="presentation"
                            tabindex="1"
                        >
                        </span>

                        <x-marketplace::seller.form.control-group.error control-name="new_password" />
                    </x-marketplace::seller.form.control-group>

                    {!! view_render_event('bagisto.seller.settings.general.password_setting.new_password.after') !!}

                    <!-- Confirm Password -->
                    <x-marketplace::seller.form.control-group class="relative">
                        <x-marketplace::seller.form.control-group.label>
                            @lang('marketplace::app.seller.settings.general.index.password-setting.confirm-password')
                        </x-marketplace::seller.form.control-group.label>

                        <x-marketplace::seller.form.control-group.control
                            type="password"
                            name="new_password_confirmation"
                            id="new_password_confirmation"
                            :label="trans('marketplace::app.seller.settings.general.index.password-setting.confirm-password')"
                            :placeholder="trans('marketplace::app.seller.settings.general.index.password-setting.confirm-password')"
                        />

                        <span 
                            class="mp-eye-icon absolute bottom-3 cursor-pointer text-2xl ltr:right-2 rtl:left-2"
                            onclick="switchVisibility('new_password_confirmation', this)"
                            role="presentation"
                            tabindex="2"
                        >

                        <x-marketplace::seller.form.control-group.error control-name="new_password_confirmation" />
                    </x-marketplace::seller.form.control-group>
                    
                    {!! view_render_event('bagisto.seller.settings.general.password_setting.confirm_password.after') !!}
                </div>

                {!! view_render_event('bagisto.seller.settings.general.password_setting.after') !!}
            </div>

            <!-- Right sub-component -->
            <div class="flex w-[360px] max-w-full flex-col gap-6 max-xl:flex-auto">
                <!-- Regional Setting -->
                {!! view_render_event('bagisto.seller.settings.general.regional_setting.before') !!}

                <div class="rounded-xl border border-[#E9E9E9] bg-white p-5">
                    <h3 class="mb-6 text-xl font-medium leading-8 text-navyBlue">
                        @lang('marketplace::app.seller.settings.general.index.regional-setting.title')
                    </h3>
                    
                    {!! view_render_event('bagisto.seller.settings.general.regional_setting.locale.before') !!}
                    
                    <!-- Locale -->
                    <x-marketplace::seller.form.control-group class="w-full">
                        <x-marketplace::seller.form.control-group.label class="required">
                            @lang('marketplace::app.seller.settings.general.index.regional-setting.locale')
                        </x-marketplace::seller.form.control-group.label>

                        <x-marketplace::seller.form.control-group.control
                            type="select"
                            id="locale"
                            name="locale"
                            rules="required"
                            :value="$seller->locale"
                        >
                            <option value="">
                                @lang('marketplace::app.seller.settings.general.index.regional-setting.select-locale')
                            </option>

                            @foreach($locales as $key => $locale)
                                <option value="{{ $key }}">
                                    {{ $locale }}
                                </option>
                            @endforeach
                        </x-marketplace::seller.form.control-group.control>

                        <x-marketplace::seller.form.control-group.error control-name="locale" />
                    </x-marketplace::seller.form.control-group>

                    {!! view_render_event('bagisto.seller.settings.general.regional_setting.locale.after') !!}
                    
                    <!-- Currency -->
                    <x-marketplace::seller.form.control-group class="w-full">
                        <x-marketplace::seller.form.control-group.label>
                            @lang('marketplace::app.seller.settings.general.index.regional-setting.currency')
                        </x-marketplace::seller.form.control-group.label>

                        <x-marketplace::seller.form.control-group.control
                            type="text"
                            id="currency"
                            name="currency"
                            :value="core()->getBaseCurrency()->code"
                            readonly="true"
                            disabled="true"
                        />
                    </x-marketplace::seller.form.control-group>

                    {!! view_render_event('bagisto.seller.settings.general.regional_setting.currency.after') !!}
                </div>

                {!! view_render_event('bagisto.seller.settings.general.regional_setting.after') !!}

            </div>
        </div>

        {!! view_render_event('bagisto.seller.settings.general.index.form_controls.after') !!}

    </x-marketplace::seller.form>

    {!! view_render_event('marketplace.seller.settings.general.index.after') !!}

    @push('scripts')
        <script>
            function switchVisibility(inputId, icon) {
                const input = document.getElementById(inputId);
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('mp-eye-icon');
                    icon.classList.add('mp-eye-off-icon');
                } else {
                    input.type = 'password';
                    icon.classList.remove('mp-eye-off-icon');
                    icon.classList.add('mp-eye-icon');
                }
            }
        </script>
    @endpush
</x-marketplace::seller.layouts>
