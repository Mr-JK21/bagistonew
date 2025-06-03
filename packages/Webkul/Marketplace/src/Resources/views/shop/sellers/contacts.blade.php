<!-- Seller Contacts Vue Component -->
<v-seller-contacts>
    <div class="flex flex-wrap max-sm:gap-3 gap-10">
        <div class="flex items-center gap-2.5">
            <span class="mp-share-icon text-2xl"></span>
    
            <span class="text-lg font-normal">
                @lang('marketplace::app.shop.sellers.profile.share.title')
            </span>
        </div>

        <div class="flex items-center gap-2.5">
            <span class="mp-phone-icon text-2xl"></span>
    
            <span class="text-lg font-normal">
                @lang('marketplace::app.shop.sellers.profile.contact-form.title')
            </span>
        </div>

        @if (core()->getConfigData('marketplace.settings.seller.flag_enabled'))
            <span class="flex items-center gap-2.5">
                <span class="mp-issue-icon text-2xl"></span>
        
                <span class="text-lg font-normal">
                    @lang('marketplace::app.shop.sellers.profile.report-form.title')
                </span>
            </span>
        @endif
    </div>
</v-seller-contacts>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-seller-contacts-template"
    >
        <div>
            <div class="flex flex-wrap max-sm:gap-3 gap-10">
                {!! view_render_event('bagisto.shop.marketplace.seller.profile.contact.before', ['seller' => $seller]) !!}
                
                <div
                    class="flex cursor-pointer items-center gap-2.5"
                    @click="$refs.socialShareModal.open()"
                >
                    <span class="mp-share-icon text-2xl"></span>
    
                    <span class="text-lg font-normal">
                        @lang('marketplace::app.shop.sellers.profile.share.title')
                    </span>
                </div>
                
                <div
                    class="flex cursor-pointer items-center gap-2.5"
                    @click="$refs.contactModal.open()"
                >
                    <span class="mp-phone-icon text-2xl"></span>
    
                    <span class="text-lg font-normal">
                        @lang('marketplace::app.shop.sellers.profile.contact-form.title')
                    </span>
                </div>
    
                @if (core()->getConfigData('marketplace.settings.seller.flag_enabled'))
                    <span
                        class="flex cursor-pointer items-center gap-2.5"
                        @click="openReportModal"
                    >
                        <span class="mp-issue-icon text-2xl"></span>
        
                        <span class="text-lg font-normal">
                            @lang('marketplace::app.shop.sellers.profile.report-form.title')
                        </span>
                    </span>
                @endif

                {!! view_render_event('bagisto.shop.marketplace.seller.profile.contact.after', ['seller' => $seller]) !!}
            </div>
            
            {!! view_render_event('bagisto.shop.marketplace.seller.profile.contact.modals', ['seller' => $seller]) !!}
            
            <!-- Social Share Modal -->
            <x-shop::modal ref="socialShareModal">
                <x-slot:header class="rounded-t-none">
                    <!-- Modal Header -->
                    <p class="text-2xl font-medium leading-10 text-[#151515]">
                        @lang('marketplace::app.shop.sellers.profile.share.share-on')
                    </p>
                </x-slot:header>
    
                <!-- Modal Content -->
                <x-slot:content class="py-6">
                    <div class="flex gap-5">
                        @foreach (['facebook', 'twitter', 'pinterest', 'linkedin'] as $social)
                            <div class="grid justify-items-center gap-2">
                                <a
                                    href="{{ $seller->{$social} ?: '#' }}"
                                    class="flex h-16 w-16 items-center justify-center rounded-full p-2.5 hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                    :class="{
                                        'bg-[#1877F2]': '{{ $social }}' === 'facebook',
                                        'bg-[#1A1A1A]': '{{ $social }}' === 'twitter',
                                        'bg-[#FFFFFF]': '{{ $social }}' === 'pinterest',
                                        'bg-[#1D8DEE]': '{{ $social }}' === 'linkedin'
                                    }"
                                >
                                    <img
                                        src="{{ bagisto_asset('images/social-icons/' . $social . '.svg', 'marketplace') }}"
                                        alt="{{ $social }} icon"
                                        class="max-w-full max-h-full object-contain"
                                        :class="{
                                            'min-w-16 min-h-16': '{{ $social }}' === 'pinterest'
                                        }"
                                    >
                                </a>
                                <span class="text-base font-medium leading-6 text-center">
                                    @lang("marketplace::app.shop.sellers.profile.share.{$social}")
                                </span>
                            </div>
                        @endforeach
                    </div>
                </x-slot:content>
            </x-shop::modal>
    
            <!-- Contact Seller Form -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    @submit="handleSubmit($event, contactSeller)"
                    ref="contactForm"
                >
                    <!-- Contact Seller Modal -->
                    <x-shop::modal ref="contactModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-2xl font-medium leading-10 text-[#151515]">
                                @lang('marketplace::app.shop.sellers.profile.contact-form.title')
                            </p>
                        </x-slot:header>
    
                        <!-- Modal Content -->
                        <x-slot:content class="!pb-2">    
                            <div class="flex gap-4">
                                <x-shop::form.control-group class="w-full">
                                    <x-shop::form.control-group.label class="required flex">
                                        @lang('marketplace::app.shop.sellers.profile.contact-form.name')
                                    </x-shop::form.control-group.label>
    
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="name"
                                        class="! shadow-none"
                                        rules="required"
                                        :placeholder="trans('marketplace::app.shop.sellers.profile.contact-form.name')"
                                    />
                                    
                                    <x-shop::form.control-group.error
                                        control-name="name"
                                        class="flex"
                                    />
                                </x-shop::form.control-group>
    
                                <x-shop::form.control-group class="w-full">
                                    <x-shop::form.control-group.label class="required flex">
                                        @lang('marketplace::app.shop.sellers.profile.contact-form.email')
                                    </x-shop::form.control-group.label>
    
                                    <x-shop::form.control-group.control
                                        type="email"
                                        name="email"
                                        class="! shadow-none"
                                        rules="required"
                                        :placeholder="trans('marketplace::app.shop.sellers.profile.contact-form.email')"
                                    />
    
                                    <x-shop::form.control-group.error
                                        control-name="email"
                                        class="flex"
                                    />
                                </x-shop::form.control-group>
                            </div>
    
                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="required flex">
                                    @lang('marketplace::app.shop.sellers.profile.contact-form.subject')
                                </x-shop::form.control-group.label>
    
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="subject"
                                    class="! shadow-none"
                                    rules="required"
                                    :placeholder="trans('marketplace::app.shop.sellers.profile.contact-form.subject')"
                                />
    
                                <x-shop::form.control-group.error
                                    control-name="subject"
                                    class="flex"
                                />
                            </x-shop::form.control-group>
    
                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="required flex">
                                    @lang('marketplace::app.shop.sellers.profile.contact-form.query')
                                </x-shop::form.control-group.label>
    
                                <x-shop::form.control-group.control
                                    type="textarea"
                                    name="query"
                                    class="! shadow-none"
                                    rules="required"
                                    :placeholder="trans('marketplace::app.shop.sellers.profile.contact-form.query')"
                                />
    
                                <x-shop::form.control-group.error
                                    control-name="query"
                                    class="flex"
                                />
                            </x-shop::form.control-group>
                        </x-slot:content>
    
                        <x-slot:footer>
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('marketplace::app.shop.sellers.profile.contact-form.submit-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-shop::modal>
                </form>
            </x-shop::form>
    
            <!-- Report Seller Form -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    @submit="handleSubmit($event, reportSeller)"
                    ref="reportForm"
                >
                    <!-- Report Seller Modal -->
                    <x-shop::modal ref="reportModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-2xl font-medium leading-9 text-[#151515]">
                                @lang('marketplace::app.shop.sellers.profile.report-form.title')
                            </p>
                        </x-slot:header>
            
                        <!-- Modal Content -->
                        <x-slot:content class="!pb-2">    
                            <!-- Condition -->
                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="required flex">
                                    @lang('marketplace::app.shop.sellers.profile.report-form.reason')
                                </x-shop::form.control-group.label>
    
                                <x-shop::form.control-group.control
                                    type="select"
                                    name="reason"
                                    class="! shadow-none"
                                    v-model="reason"
                                    rules="required"
                                >
                                    <option value="">
                                        @lang('marketplace::app.shop.sellers.profile.report-form.select-reason')
                                    </option>

                                    @foreach ($flagReasons as $reason)
                                        <option value="{{ $reason->name }}">
                                            {{ $reason->name }}
                                        </option>
                                    @endforeach

                                    <option value="other">
                                        @lang('marketplace::app.shop.sellers.profile.report-form.other-reason')
                                    </option>
                                </x-shop::form.control-group.control>
    
                                <template v-if="reason == 'other'">
                                    <x-shop::form.control-group.control
                                        type="textarea"
                                        name="other_reason"
                                        class="! shadow-none"
                                        rules="required|max:200"
                                        :label="trans('marketplace::app.shop.sellers.profile.report-form.reason')"
                                        :placeholder="trans('marketplace::app.shop.sellers.profile.report-form.reason-placeholder')"
                                    />
                                </template>
    
                                <v-error-message
                                    :name="reason == 'other' ? 'other_reason' : 'reason'"
                                    v-slot="{ message }"
                                >
                                    <p class="text-xs italic text-red-500">
                                        @{{ message }}
                                    </p>
                                </v-error-message>
                            </x-shop::form.control-group>
                        </x-slot::content>
    
                        <x-slot:footer>
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('marketplace::app.shop.sellers.profile.report-form.submit-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-shop::modal>
                </form>
            </x-shop::form>
        </div>
    </script>

    <script type="module">
        app.component('v-seller-contacts', {
            template: '#v-seller-contacts-template',

            data() {
                return {
                    reason: '',

                    customer: @json(auth()->guard('customer')->user())
                }
            },

            methods: {
                contactSeller(params, { resetForm, setErrors }) {
                    let formData = new FormData(this.$refs.contactForm);                     

                    this.$axios.post("{{route('shop.marketplace.sellers.contact', $seller->shop_url)}}", formData)
                        .then((response) => {
                            this.$refs.contactForm.reset();
                            
                            this.$refs.contactModal.close();

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

                reportSeller(params, { resetForm, setErrors }) {
                    let formData = new FormData(this.$refs.reportForm);                     

                    this.$axios.post("{{route('shop.marketplace.sellers.flag', $seller->shop_url)}}", formData)
                        .then((response) => {
                            this.$refs.reportForm.reset();
                            
                            this.$refs.reportModal.close();

                            this.$emitter.emit('add-flash', {
                                type: response.data.type,
                                message: response.data.message
                            });
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

                openReportModal() {
                    if (! this.customer) {
                        this.$emitter.emit('open-confirm-modal', {
                            title: "@lang('marketplace::app.shop.sellers.profile.report-form.alert')",
                            message: "@lang('marketplace::app.shop.sellers.profile.report-form.guest-alert')",
                            agree: () => {
                                window.location.href = "{{ route('shop.customer.session.index') }}";
                            }
                        });

                        return;
                    }

                    this.$refs.reportModal.open();
                }
            }
        });
    </script>
@endPushOnce