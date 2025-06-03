@inject ('flagReasonRepository', 'Webkul\Marketplace\Repositories\FlagReasonRepository')

@php
    $flagReasons = $flagReasonRepository->productType()->get();
@endphp

<!-- Product Report Vue Component -->
@if (core()->getConfigData('marketplace.settings.product.flag_enabled'))
    <v-product-report :seller-product="sellerProduct"></v-product-report>
@endif

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-report-template"
    >
        <div class="mt-0 flex flex-wrap gap-3 sm:mt-3 md:gap-10">
            <span
                class="flex cursor-pointer items-center gap-2.5"
                @click="openReportModal"
            >
                <span class="mp-issue-icon text-2xl"></span>

                <span class="text-lg font-medium text-navyBlue">
                    @lang('marketplace::app.shop.products.flag.report-product')
                </span>
            </span>
        </div>

        <!-- Report Seller Form -->
        <x-shop::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form
                @submit="handleSubmit($event, reportProduct)"
                ref="reportForm"
            >
                <!-- Report Seller Modal -->
                <x-shop::modal ref="reportModal">
                    <x-slot:header>
                        <!-- Modal Header -->
                        <p class="text-2xl font-medium leading-9 text-[#151515]">
                            @lang('marketplace::app.shop.products.flag.report-product')
                        </p>
                    </x-slot:header>
        
                    <!-- Modal Content -->
                    <x-slot:content class="!pb-2">
                        <!-- Reason -->
                        <x-shop::form.control-group class="w-full">
                            <x-shop::form.control-group.label class="required flex">
                                @lang('marketplace::app.shop.products.flag.reason')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="select"
                                name="reason"
                                class="! shadow-none"
                                rules="required|max:200"
                                v-model="reason"
                                :placeholder="trans('marketplace::app.shop.products.flag.reason')"
                            >
                                <option value="">
                                    @lang('marketplace::app.shop.products.flag.select-reason')
                                </option>

                                @foreach ($flagReasons as $reason)
                                    <option value="{{ $reason->name }}">
                                        {{ $reason->name }}
                                    </option>
                                @endforeach

                                <option value="other">
                                    @lang('marketplace::app.shop.products.flag.other-reason')
                                </option>
                            </x-shop::form.control-group.control>

                            <template v-if="reason == 'other'">
                                <x-shop::form.control-group.control
                                    type="textarea"
                                    name="other_reason"
                                    class="! shadow-none"
                                    rules="required|max:200"
                                    :label="trans('marketplace::app.shop.products.flag.reason')"
                                    :placeholder="trans('marketplace::app.shop.products.flag.reason-placeholder')"
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
                        <div class="flex justify-end pb-4">
                            <button
                                type="submit"
                                class="primary-button"
                            >
                                @lang('marketplace::app.shop.products.flag.submit-btn')
                            </button>
                        </div>
                    </x-slot:footer>
                </x-shop::modal>
            </form>
        </x-shop::form>
    </script>

    <script type="module">
        app.component('v-product-report', {
            template: '#v-product-report-template',

            props: {
                sellerProduct: {
                    type: Object,
                    required: true
                }
            },

            data() {
                return {
                    reason: '',

                    customer: @json(auth()->guard('customer')->user())
                }
            },
            
            methods: {
                reportProduct(params, { resetForm, setErrors }) {                    
                    let formData = new FormData(this.$refs.reportForm);

                    formData.append('marketplace_product_id', this.sellerProduct.id);

                    this.$axios.post("{{route('shop.marketplace.products.flag.store')}}", formData)
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
                            title: "@lang('marketplace::app.shop.products.flag.alert')",
                            message: "@lang('marketplace::app.shop.products.flag.guest-alert')",
                            agree: () => {
                                window.location.href = "{{ route('shop.customer.session.index') }}";
                            }
                        });

                        return;
                    }

                    this.$refs.reportModal.open();
                },
            }
        });
    </script>
@endPushOnce