<x-marketplace::seller.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.seller.settings.roles.edit.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::seller.breadcrumbs name="roles.edit" />
    @endSection

    {!! view_render_event('marketplace.seller.settings.roles.edit.before', ['role' => $role]) !!}

    <x-marketplace::seller.form
        method="PUT"
        :action="route('seller.settings.roles.edit', $role->id)"
    >

        {!! view_render_event('bagisto.seller.settings.roles.edit.create_form_controls.before', ['role' => $role]) !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-2xl font-medium">
                @lang('marketplace::app.seller.settings.roles.edit.title')
            </p>

            {!! view_render_event('bagisto.seller.settings.roles.edit.create_form_controls.buttons.before', ['role' => $role]) !!}

            <div class="flex items-center gap-x-2.5">
                {!! view_render_event('bagisto.seller.settings.roles.edit.create_form_controls.back_button.before', ['role' => $role]) !!}

                <!-- Back Button -->
                <a
                    href="{{ route('seller.settings.roles.index') }}"
                    class="transparent-button rounded-xl px-5 py-2.5 font-semibold hover:bg-gray-200"
                >
                    @lang('marketplace::app.seller.settings.roles.edit.back-btn')
                </a>

                {!! view_render_event('bagisto.seller.settings.roles.edit.create_form_controls.back_button.after', ['role' => $role]) !!}

                {!! view_render_event('bagisto.seller.settings.roles.edit.create_form_controls.buttons.save_button.before', ['role' => $role]) !!}

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button px-5 py-2.5"
                >
                    @lang('marketplace::app.seller.settings.roles.edit.save-btn')
                </button>

                {!! view_render_event('bagisto.seller.settings.roles.edit.create_form_controls.buttons.save_button.after', ['role' => $role]) !!}
            </div>

            {!! view_render_event('bagisto.seller.settings.roles.edit.create_form_controls.buttons.after', ['role' => $role]) !!}
        </div>

         <!-- body content -->
         <div class="mt-3.5 flex gap-6 max-xl:flex-wrap">
            <!-- Left sub-component -->
            <div class="flex flex-1 flex-col gap-6 max-xl:flex-auto">

                {!! view_render_event('bagisto.seller.settings.roles.edit.card.access_control.before', ['role' => $role]) !!}

                <!-- Access Control Input Fields -->
                <div class="rounded-xl border border-[#E9E9E9] bg-white p-5">
                    <p class="mb-4 text-base font-semibold text-gray-800">
                        @lang('marketplace::app.seller.settings.roles.edit.access-control')
                    </p>

                    <!-- Create Role for -->
                    <v-mp-access-control>
                        <!-- Shimmer Effect -->
                        <div class="mb-4">
                            <div class="shimmer mb-1.5 h-4 w-24"></div>

                            <div class="custom-select h-11 w-full rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400"></div>
                        </div>
                    </v-mp-access-control>
                </div>

                {!! view_render_event('bagisto.seller.settings.roles.edit.card.access_control.after', ['role' => $role]) !!}

            </div>

            <!-- Right sub-component -->
            <div class="flex w-[360px] max-w-full flex-col gap-6 max-xl:flex-auto">

                {!! view_render_event('bagisto.seller.settings.roles.edit.card.general.before', ['role' => $role]) !!}

                <div class="rounded-xl border border-[#E9E9E9] bg-white p-5">
                    <p class="p-2.5 text-base font-semibold text-gray-800">
                        @lang('marketplace::app.seller.settings.roles.edit.general')
                    </p>

                    {!! view_render_event('bagisto.seller.settings.roles.edit.card.general.name.before', ['role' => $role]) !!}

                    <!-- Name -->
                    <x-marketplace::seller.form.control-group>
                        <x-marketplace::seller.form.control-group.label class="required">
                            @lang('marketplace::app.seller.settings.roles.edit.name')
                        </x-marketplace::seller.form.control-group.label>

                        <x-marketplace::seller.form.control-group.control
                            type="text"
                            id="name"
                            name="name"
                            rules="required"
                            value="{{ old('name') ?: $role->name }}"
                            :label="trans('marketplace::app.seller.settings.roles.edit.name')"
                            :placeholder="trans('marketplace::app.seller.settings.roles.edit.name')"
                        />

                        <x-marketplace::seller.form.control-group.error control-name="name" />
                    </x-marketplace::seller.form.control-group>

                    {!! view_render_event('bagisto.seller.settings.roles.edit.card.general.name.after', ['role' => $role]) !!}

                    {!! view_render_event('bagisto.seller.settings.roles.edit.card.general.description.before', ['role' => $role]) !!}

                    <!-- Description -->
                    <x-marketplace::seller.form.control-group class="!mb-0">
                        <x-marketplace::seller.form.control-group.label class="required">
                            @lang('marketplace::app.seller.settings.roles.edit.description')
                        </x-marketplace::seller.form.control-group.label>

                        <x-marketplace::seller.form.control-group.control
                            type="textarea"
                            id="description"
                            name="description"
                            rules="required"
                            value="{{ old('description') ?: $role->description }}"
                            :label="trans('marketplace::app.seller.settings.roles.edit.description')"
                            :placeholder="trans('marketplace::app.seller.settings.roles.edit.description')"
                        />

                        <x-marketplace::seller.form.control-group.error control-name="description" />
                    </x-marketplace::seller.form.control-group>

                    {!! view_render_event('bagisto.seller.settings.roles.edit.card.general.description.after', ['role' => $role]) !!}
                </div>

                {!! view_render_event('bagisto.seller.settings.roles.edit.card.general.after', ['role' => $role]) !!}

            </div>
        </div>

        {!! view_render_event('bagisto.seller.settings.roles.edit.create_form_controls.after', ['role' => $role]) !!}

    </x-marketplace::seller.form>

    {!! view_render_event('bagisto.seller.settings.roles.edit.after', ['role' => $role]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-mp-access-control-template"
        >
            <div>
                <!-- Permission Type -->
                <x-marketplace::seller.form.control-group>
                    <x-marketplace::seller.form.control-group.label class="required">
                        @lang('marketplace::app.seller.settings.roles.edit.permissions')
                    </x-marketplace::seller.form.control-group.label>

                    <x-marketplace::seller.form.control-group.control
                        type="select"
                        name="permission_type"
                        id="permission_type"
                        rules="required"
                        :label="trans('marketplace::app.seller.settings.roles.edit.permissions')"
                        :placeholder="trans('marketplace::app.seller.settings.roles.edit.permissions')"
                        v-model="permission_type"
                    >
                        <option value="custom">
                            @lang('marketplace::app.seller.settings.roles.edit.custom')
                        </option>

                        <option value="all">
                            @lang('marketplace::app.seller.settings.roles.edit.all')
                        </option>
                    </x-marketplace::seller.form.control-group.control>

                    <x-marketplace::seller.form.control-group.error control-name="permission_type" />
                </x-marketplace::seller.form.control-group>

                <div v-if="permission_type == 'custom'">
                    <x-marketplace::seller.tree.view
                        input-type="checkbox"
                        value-field="key"
                        id-field="key"
                        :items="json_encode(marketplace_acl()->getItems())"
                        :value="json_encode($role->permissions ?? [])"
                    />
                </div>

                <x-marketplace::seller.form.control-group.error control-name="permissions" />
            </div>
        </script>

        <script type="module">
            app.component('v-mp-access-control', {
                template: '#v-mp-access-control-template',

                data() {
                    return {
                        permission_type: @json($role->permission_type)
                    };
                }
            })
        </script>
    @endPushOnce
</x-marketplace::seller.layouts>
