<marketplace-seller-info-here>
    @inject ('reviewRepository', 'Webkul\Marketplace\Repositories\ReviewRepository')
    @inject ('productRepository', 'Webkul\Marketplace\Repositories\ProductRepository')

    @php
        $sellerProduct = $productRepository->with('seller')->findOneWhere([
            'product_id' => $product->id,
            'is_owner'   => 1,
        ]);

        if ($sellerProduct) {
            $avgRatings = $reviewRepository->getAverageRating($sellerProduct->seller);
        }

        if ($product->type != 'configurable') {
            $count = $productRepository->getSellerCount($product);
        } else {
            $variants = [];
        
            foreach ($product->variants as $variant) {
                $variants[$variant->id] = $productRepository->getSellerCount($variant);
            }
        }
    @endphp

    <v-product-sellers>
        <div class="mt-8 flex flex-col gap-y-1">
            @if ($sellerProduct)
                <div class="shimmer h-7 w-10"></div>

                <div class="shimmer h-7 w-40"></div>

                <x-marketplace::shop.shimmer.ratings />

                <div class="shimmer h-6 w-[170px]"></div>
            @endif

            <div class="shimmer h-7 w-40"></div>
        </div>
    </v-product-sellers>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-sellers-template"
        >
            <div class="mt-8 flex flex-col gap-y-1">
                <template v-if="sellerProduct">
                    <div class="text-lg font-medium text-[#757575]">
                        @lang('marketplace::app.shop.products.sold-by')
                    </div>
        
                    <a
                        class="text-lg font-semibold text-navyBlue"
                        :href="'{{ route('shop.marketplace.sellers.show', ':url') }}'.replace(':url', sellerProduct.seller.url)"
                    >
                        @{{ sellerProduct.seller.shop_title }}
                    </a>
        
                    <x-marketplace::shop.products.star-rating
                        name="Seller Rating"
                        ::value="avgRatings"
                    />
        
                    <!-- Product flag Blade File (If seller in owner) -->
                    @include('marketplace::shop.sellers.products.report')
                </template>
    
                <a
                    v-if="
                        product.type != 'configurable'
                        && count > 0
                    "
                    class="text-lg font-normal text-navyBlue"
                    :href="'{{ route('shop.marketplace.products.offers.index', ':id') }}'.replace(':id', product.id)"
                >
                    @{{ "@lang('marketplace::app.shop.products.seller-count')".replace(':count', count) }}
                </a>
            </div>

            <a
                class="text-lg font-normal text-navyBlue"
                :href="actionUrl"
                v-if="visible"
            >
                @{{ "@lang('marketplace::app.shop.products.seller-count')".replace(':count', count) }}
            </a>
        </script>
        
        <script type="module">
            app.component('v-product-sellers', {
                template: '#v-product-sellers-template',

                data() {
                    return {
                        visible: false,

                        variants: @json($variants ?? []),

                        actionUrl: '',

                        count: @json($count ?? 0),

                        product: @json($product),

                        sellerProduct: @json($sellerProduct),

                        avgRatings: @json($avgRatings ?? 0),
                    }
                },

                created() {
                    this.listenEvents();
                },

                methods: {
                    listenEvents(key) {
                        this.$emitter.on('configurable-variant-selected-event', (variantId) => {
                            if (this.variants[variantId]) {
                                let url = "{{ route('shop.marketplace.products.offers.index', ':product_id') }}";
                                
                                this.actionUrl = url.replace(':product_id', variantId);

                                this.count = this.variants[variantId];
                                
                                this.visible = true;
                            } else {
                                this.visible = false;
                            }
                        });
                    }
                }
            });
        </script>
    @endPushOnce
</marketplace-seller-info-here>
