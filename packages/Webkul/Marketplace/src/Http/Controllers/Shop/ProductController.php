<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Webkul\Marketplace\Repositories\ProductDownloadableLinkRepository;
use Webkul\Marketplace\Repositories\ProductDownloadableSampleRepository;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\ReviewRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected BaseProductRepository $baseProductRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        protected ProductRepository $productRepository,
        protected SellerRepository $sellerRepository,
        protected ReviewRepository $reviewRepository
    ) {}

    /**
     * Product offers by sellers
     *
     * @return mixed
     */
    public function offers(int $id)
    {
        $product = $this->baseProductRepository->findOrFail($id);

        if ($product->type == 'configurable') {
            session()->flash('error', trans('product::app.checkout.cart.missing-options'));

            return to_route('shop.product_or_category.index', $product->url_key);
        }

        $sellerProducts = $this->productRepository->getSellerProducts($product);

        if (! $sellerProducts->count()) {
            return to_route('shop.home.index');
        }

        return view('marketplace::shop.products.offers')
            ->with([
                'product'        => $product,
                'sellerProducts' => $sellerProducts,
            ]);
    }

    /**
     * Download the for the specified resource.
     *
     * @return Response|\Exception
     */
    public function downloadSample()
    {
        try {
            if (request('type') == 'link') {
                $productDownloadableLink = $this->productDownloadableLinkRepository->findOrFail(request('id'));

                if ($productDownloadableLink->sample_type == 'file') {
                    $privateDisk = Storage::disk('private');

                    return $privateDisk->exists($productDownloadableLink->sample_file)
                        ? $privateDisk->download($productDownloadableLink->sample_file)
                        : abort(404);
                }

                $fileName = substr($productDownloadableLink->sample_url, strrpos($productDownloadableLink->sample_url, '/') + 1);

                $tempImage = tempnam(sys_get_temp_dir(), $fileName);

                copy($productDownloadableLink->sample_url, $tempImage);

                return response()->download($tempImage, $fileName);
            }

            $productDownloadableSample = $this->productDownloadableSampleRepository->findOrFail(request('id'));

            if ($productDownloadableSample->type == 'file') {
                return Storage::download($productDownloadableSample->file);
            }

            $fileName = substr($productDownloadableSample->url, strrpos($productDownloadableSample->url, '/') + 1);

            $tempImage = tempnam(sys_get_temp_dir(), $fileName);

            copy($productDownloadableSample->url, $tempImage);

            return response()->download($tempImage, $fileName);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return mixed
     */
    public function topSellingProducts(int $sellerId): JsonResource
    {
        return ProductResource::collection($this->baseProductRepository
            ->findWhereIn('id', $this->productRepository->getTopSellingProducts($sellerId)));
    }
}
