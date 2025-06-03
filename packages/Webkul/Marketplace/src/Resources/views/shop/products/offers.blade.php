@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('cartHelper', 'Webkul\Marketplace\Helpers\Cart')
@inject ('sellerRepository', 'Webkul\Marketplace\Repositories\SellerRepository')

@php
    $avgRatings = round($reviewHelper->getAverageRating($product));

    $baseProduct = $product->parent_id ? $product->parent : $product;

    $productBaseImage = product_image()->getProductBaseImage($product);
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {{ app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) }}
        </script>
    @endif

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    <!-- Breadcrumbs -->
    <div class="flex justify-center max-lg:hidden">
        <x-shop::breadcrumbs name="product" :entity="$product"></x-shop::breadcrumbs>
    </div>

    <div class="container px-[60px] max-1180:px-0">
        <div class="mt-12 flex gap-9 max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-4">
            <div class="w-full max-w-[560px] sm:max-h-[610px]">
                <img
                    src="{{ $productBaseImage['medium_image_url'] }}"
                    class="w-full h-auto cursor-pointer md:rounded-xl object-cover"
                    alt="Product Image"
                    width="560"
                    height="609"
                />
            </div>            

            <div class="relative w-full max-w-full px-5">
                <div class="flex flex-col gap-y-4 mb-4">
                    <h1 class="text-3xl font-medium text-[#000000]">
                        {{ $baseProduct->name }}
                    </h1>

                    @if ($baseProduct->type === 'configurable')
                        <div class="text-base font-normal text-navyBlue">
                            @php
                                $options = $cartHelper->getAdditionalOptions($product);
                            @endphp

                            @foreach ($options as $option)
                                <p class="text-base font-normal text-[#6E6E6E]">
                                    {{ $option['attribute_name'] }}: {{ $option['option_label'] }}
                                </p>
                            @endforeach
                        </div>
                    @endif
        
                    <div class="flex items-center gap-2.5">
                        <x-marketplace::shop.products.star-rating 
                            :value="$avgRatings"
                            :is-editable=false
                        />
        
                        <p class="text-sm text-[#6E6E6E]">
                            ({{ $product->approvedReviews->count() }} @lang('reviews'))
                        </p>
                    </div>
                </div>

                <v-more-sellers :seller-products="{{ json_encode($sellerProducts) }}">
                    @foreach ($sellerProducts as $sellerProduct)
                        <x-marketplace::shop.shimmer.more-seller />
                    @endforeach
                </v-more-sellers>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-more-sellers-template"
        >
            <v-more-seller
                v-for="(sellerProduct, index) in sellerProducts"
                :key="index"
                :seller-product="sellerProduct"
            >
                @foreach ($sellerProducts as $sellerProduct)
                    <x-marketplace::shop.shimmer.more-seller />
                @endforeach
            </v-more-seller>
        </script>

        <script type="module">
            app.component('v-more-sellers', {
                template: '#v-more-sellers-template',

                props: {
                    sellerProducts: {
                        type: Object,
                        required: true
                    },
                },
            });
        </script>

        <script
            type="text/x-template"
            id="v-more-seller-template"
        >
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    ref="formData"
                    @submit="handleSubmit($event, addToCart)"
                >
                    <div class="rounded-md border p-5 mb-5">
                        <div class="flex max-sm:flex-col justify-between gap-2.5">
                            <div class="flex flex-col gap-2.5">
                                <a
                                    :href="`{{ route('shop.marketplace.sellers.show', '') }}/${sellerProduct.seller.url}`"
                                    class="text-xl font-medium"
                                >
                                    @{{ sellerProduct.seller.shop_title}}
                                </a>

                                <x-marketplace::shop.products.star-rating 
                                    ::value="sellerProduct.avg_rating"
                                    :is-editable="false"
                                />
                                
                                <p class="text-2xl font-medium">
                                    @{{ sellerProduct.formatted_price }}
                                </p>

                                <p class="text-base font-normal">
                                    @lang('marketplace::app.shop.products.delivery-info')
                                </p>
                            </div>

                            <div class="flex flex-col gap-2.5">
                                <template v-if="product.type === 'downloadable'">
                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="1"
                                    />

                                    <div
                                        class="sample-list"
                                        v-if="sellerProduct.downloadable_samples?.length"
                                    >
                                        <label class="mb-3 flex font-medium">
                                            @lang('shop::app.products.view.type.downloadable.samples')
                                        </label>

                                        <ul>
                                            <li
                                                class="mb-2"
                                                v-for="sample in sellerProduct.downloadable_samples"
                                            >
                                                <a 
                                                    :href="'{{ route('shop.marketplace.products.offers.download_sample', ['type' => 'sample', 'id' => ':id']) }}'.replace(':id', sample.id)" 
                                                    class="text-blue-700"
                                                    target="_blank"
                                                >
                                                    @{{ sample.title }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <template v-if="sellerProduct.downloadable_links?.length">
                                        <label class="flex font-medium">
                                            @lang('shop::app.products.view.type.downloadable.links')
                                        </label>

                                        <div class="flex flex-col gap-4 max-sm:gap-1">
                                            <div
                                                class="flex select-none items-center gap-x-4"
                                                v-for="link in sellerProduct.downloadable_links"
                                            >
                                                <div class="flex items-center">
                                                    <v-field
                                                        type="checkbox"
                                                        name="links[]"
                                                        :value="link.id"
                                                        :id="link.id"
                                                        class="peer hidden"
                                                        rules="required"
                                                        label="@lang('shop::app.products.view.type.downloadable.links')"
                                                    >
                                                    </v-field>
                                                    
                                                    <label
                                                        class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue"
                                                        :for="link.id"
                                                    ></label>
                                                    
                                                    <label
                                                        :for="link.id"
                                                        class="cursor-pointer max-sm:text-sm ltr:ml-1 rtl:mr-1"
                                                    >
                                                        @{{ `${link.title} + ${link.formatted_price}` }}
                                                    </label>
                                                </div>

                                                <a
                                                    v-if="
                                                        link.sample_file
                                                        || link.sample_url
                                                    "
                                                    :href="'{{ route('shop.marketplace.products.offers.download_sample', ['type' => 'link', 'id' => ':id']) }}'.replace(':id', link.id)"
                                                    target="_blank"
                                                    class="text-blue-700 max-sm:text-sm"
                                                >
                                                    @lang('shop::app.products.view.type.downloadable.sample')
                                                </a>
                                            </div>

                                            <v-error-message
                                                name="links[]"
                                                v-slot="{ message }"
                                            >
                                                <p class="text-xs italic text-red-500">
                                                    @{{ message }}
                                                </p>
                                            </v-error-message>
                                        </div>
                                    </template>
                                </template>

                                <div class="grid max-sm:grid-cols-2 gap-2.5">
                                    <x-shop::quantity-changer
                                        v-if="['simple', 'virtual'].includes(product.type)"
                                        name="quantity"
                                        value="1"
                                        class="gap-x-4 rounded-xl px-7 py-4 max-md:py-3 max-sm:gap-x-5 max-sm:rounded-lg max-sm:px-4 max-sm:py-1.5 justify-center"
                                    />

                                    <x-shop::button
                                        type="submit"
                                        class="secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5"
                                        button-type="secondary-button"
                                        :title="trans('marketplace::app.shop.products.add-to-cart')"
                                        ::disabled="! sellerProduct.is_saleable"
                                        ::loading="isStoring"
                                    />
                                </div>

                                <div class="flex flex-col gap-1 items-end">
                                    <!-- Product flag Blade File -->
                                    @include('marketplace::shop.sellers.products.report')

                                    <p
                                        class="cursor-pointer text-base font-medium text-navyBlue"
                                        @click="showMore = ! showMore"
                                    >
                                        <template v-if="showMore === false">
                                            @lang('marketplace::app.shop.products.more-info')
                                        </template>

                                        <template v-else>
                                            @lang('marketplace::app.shop.products.hide')
                                        </template>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="grid gap-y-6 transition-all duration-300"
                            v-show="showMore"
                        >
                            <div class="mt-6 grid max-sm:grid-cols-2 gap-3 grid-cols-4">
                                <img
                                    v-for="image in sellerProduct.images"
                                    class="aspect-square rounded"
                                    :src="`{{ Storage::url('') }}${image.path}`"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <video
                                    v-for="video in sellerProduct.videos"
                                    controls
                                    width="100%"
                                    height="auto"
                                >
                                    <source
                                        :src="`{{ Storage::url('') }}${video.path}`"
                                        type="video/mp4"
                                    />
                                </video>
                            </div>

                            <p
                                class="text-base text-[#000000]"
                                v-html="sellerProduct.description"
                            >
                            </p>

                            <hr>

                            <p class="text-base text-[#757575]">
                                @lang('marketplace::app.shop.products.seller-info')
                                <br>
                                @{{ sellerProduct.seller.shop_title }}
                                <br>
                                @{{ sellerProduct.seller.full_address }}
                            </p>
                        </div>
                    </div>
                </form>
            </x-shop::form>
        </script>

        <script type="module">
            app.component('v-more-seller', {
                template: '#v-more-seller-template',

                props: {
                    sellerProduct: {
                        type: Object,
                        required: true
                    },
                },

                data() {
                    return {
                        showMore: false,

                        isStoring: false,

                        product: @json($product),
                    }
                },

                methods: {
                    addToCart(params, { resetForm }) {
                        this.isStoring = true;

                        let formData = new FormData(this.$refs.formData);

                        formData.append('id', this.sellerProduct.id);

                        this.$axios.post('{{ route("shop.marketplace.checkout.cart.store") }}', formData)
                            .then(response => {
                                if (response.data.message) {
                                    this.$emitter.emit('update-mini-cart', response.data.data);

                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: response.data.message
                                    });
                                } else {
                                    this.$emitter.emit('add-flash', {
                                        type: 'warning',
                                        message: response.data.data.message
                                    });
                                }
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', {
                                    type: 'warning',
                                    message: error.response.data.message
                                });
                            })
                            .finally(() => {
                                this.isStoring = false;

                                resetForm();
                            });
                    },
                }
            });
        </script>
    @endPushOnce
</x-shop::layouts>