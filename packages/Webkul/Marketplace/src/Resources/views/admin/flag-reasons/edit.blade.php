<x-admin::layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.flag-reasons.edit.title')
    </x-slot>

    {!! view_render_event('marketplace.admin.flag_reasons.edit.before') !!}

    <!-- Input Form -->
    <x-admin::form
        :action="route('admin.marketplace.flag_reasons.update', $flagReason->id)"
        method="PUT"
    >
        {!! view_render_event('marketplace.admin.flag_reasons.edit.create_form_controls.before') !!}

        <!-- actions buttons -->
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('marketplace::app.admin.flag-reasons.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.marketplace.flag_reasons.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('marketplace::app.admin.flag-reasons.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('marketplace::app.admin.flag-reasons.edit.save-btn')
                </button>
            </div>
        </div>
        
        <!-- body content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">

            {!! view_render_event('marketplace.admin.flag_reasons.edit.card.label.before') !!}

            <!-- Left sub Component -->
            <div class="flex flex-1 flex-col gap-2 overflow-auto max-xl:flex-auto">
                <!-- Label -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('marketplace::app.admin.flag-reasons.edit.label')
                    </p>

                    <!-- Admin name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('marketplace::app.admin.flag-reasons.edit.admin')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="admin_name"
                            rules="required"
                            :value="old('admin_name', $flagReason->admin_name)"
                            :label="trans('marketplace::app.admin.flag-reasons.edit.admin')"
                            :placeholder="trans('marketplace::app.admin.flag-reasons.edit.admin')"
                        />

                        <x-admin::form.control-group.error control-name="admin_name" />
                    </x-admin::form.control-group>

                    <!-- Locales Inputs -->
                    @foreach ($locales as $locale)
                        <x-admin::form.control-group class="last:!mb-0">
                            <x-admin::form.control-group.label>
                                {{ $locale->name . ' (' . strtoupper($locale->code) . ')' }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                :name="$locale->code . '[name]'"
                                :value="old($locale->code . '[name]') ?: ($flagReason->translate($locale->code)->name ?? '')"
                                :placeholder="$locale->name"
                            />
                        </x-admin::form.control-group>
                    @endforeach
                </div>
            </div>

            {!! view_render_event('marketplace.admin.flag_reasons.edit.card.label.after') !!}

            {!! view_render_event('marketplace.admin.flag_reasons.edit.card.general.before') !!}

            <!-- Right sub-component -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">
                <!-- General -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('marketplace::app.admin.flag-reasons.edit.general')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <!-- Flag Type -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('marketplace::app.admin.flag-reasons.edit.type')
                            </x-admin::form.control-group.label>

                            @php
                                $selectedType = old('type', $flagReason->type);
                            @endphp

                            <x-admin::form.control-group.control
                                type="select"
                                id="type"
                                class="cursor-pointer"
                                name="type"
                                rules="required"
                                :value="$selectedType"
                                :label="trans('marketplace::app.admin.flag-reasons.edit.type')"
                            >
                                @foreach(\Webkul\Marketplace\Enums\FlagReasonType::values() as $type)
                                    <option
                                        value="{{ $type }}"
                                        {{ $type == $selectedType ? 'selected' : '' }}
                                    >
                                        @lang('marketplace::app.admin.flag-reasons.edit.'. $type)
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="type" />
                        </x-admin::form.control-group>

                        <input
                            type="hidden"
                            name="status"
                            value="0"
                        />

                        <!-- Textarea Switcher -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('marketplace::app.admin.flag-reasons.edit.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="status"
                                value="1"
                                :checked="(bool) old('status', $flagReason->status)"
                            />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>
            </div>

            {!! view_render_event('marketplace.admin.flag_reasons.edit.card.general.after') !!}

        </div>

        {!! view_render_event('marketplace.admin.flag_reasons.create_form_controls.after') !!}

    </x-admin::form>

    {!! view_render_event('marketplace.admin.flag_reasons.edit.after') !!}
</x-admin::layouts>
