<?php

namespace Webkul\Marketplace\Http\Controllers\Seller\Products;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\ProductForm;
use Webkul\Admin\Http\Resources\AttributeResource;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Rules\Slug;
use Webkul\Marketplace\DataGrids\Seller\ProductDataGrid;
use Webkul\Marketplace\Helpers\Product\ProgressBar as ProductProgressBar;
use Webkul\Marketplace\Http\Controllers\Seller\Controller;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerCategoryRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Helpers\ProductType;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected BaseProductRepository $baseProductRepository,
        protected CategoryRepository $categoryRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        protected ProductRepository $productRepository,
        protected SellerCategoryRepository $sellerCategoryRepository,
        protected SellerRepository $sellerRepository,
        protected ProductProgressBar $productProgressBar
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ProductDataGrid::class)->process();
        }

        return view('marketplace::seller.products.index');
    }

    /**
     * Search products to assign.
     */
    public function search(): JsonResponse
    {
        $results = [];

        $seller = seller()->user();

        $products = $this->productRepository->searchProducts(request()->input('query'), $seller);

        foreach ($products as $row) {
            $results[] = [
                'id'              => $row->id,
                'sku'             => $row->sku,
                'name'            => $row->name,
                'formatted_price' => core()->formatBasePrice($row->getTypeInstance()->getMinimalPrice()),
                'base_image'      => $row->images->first()
                    ? $row->images->first()->url
                    : null,
            ];
        }

        return new JsonResponse($results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $configurableFamily = null;

        $seller = seller()->user();

        if (! $seller->is_profile_completed) {
            return back()->with('warning', trans('marketplace::app.seller.products.create.update-profile'));
        }

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamilyRepository->find($familyId);
        }

        return view('marketplace::seller.products.create')
            ->with([
                'families'                  => $this->attributeFamilyRepository->all(),
                'configurableFamily'        => $configurableFamily,
                'sellerAllowedProductTypes' => $this->sellerRepository->getAllowedProducts(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (empty(core()->getConfigData('marketplace.settings.product.seller_can_create'))) {
            return;
        }

        $this->validate(request(), [
            'type'                => 'required',
            'attribute_family_id' => 'required',
            'sku'                 => ['required', 'unique:products,sku', new Slug],
            'super_attributes'    => 'array|min:1',
            'super_attributes.*'  => 'array|min:1',
        ]);

        if (
            ProductType::hasVariants(request()->input('type'))
            && ! request()->has('super_attributes')
        ) {
            $configurableFamily = $this->attributeFamilyRepository->find(request()->input('attribute_family_id'));

            return response()->json([
                'attributes' => AttributeResource::collection($configurableFamily->configurable_attributes),
            ]);
        }

        if (! $this->sellerRepository->getAllowedProducts()->has(request()->input('type'))) {
            return response()->json([
                'message' => trans('marketplace::app.seller.products.product-type-not-allowed', ['type' => request()->input('type')]),
            ]);
        }

        Event::dispatch('catalog.product.create.before');

        $product = $this->baseProductRepository->create(request()->only([
            'type',
            'attribute_family_id',
            'sku',
            'super_attributes',
            'family',
        ]));

        Event::dispatch('catalog.product.create.after', $product);

        $this->productRepository->create([
            'product_id' => $product->id,
            'is_owner'   => 1,
        ]);

        session()->flash('success', trans('marketplace::app.seller.products.create-success'));

        return response()->json([
            'redirect_url' => route('seller.products.edit', $product->id),
        ]);
    }

    /**
     * Search simple products.
     */
    public function searchSimple(): JsonResponse
    {
        $results = [];

        $params = array_merge(request()->query(), [
            'name'      => request('query'),
            'sort'      => 'created_at',
            'order'     => 'desc',
            'seller_id' => seller()->user()->id,
        ]);

        $products = $this->productRepository->searchFromDatabase($params);

        foreach ($products as $product) {
            $results[] = [
                'id'              => $product->id,
                'sku'             => $product->sku,
                'name'            => $product->name,
                'price'           => $product->price,
                'formatted_price' => core()->formatBasePrice($product->price),
                'images'          => $product->images,
                'inventories'     => $product->inventories,
            ];
        }

        $products->setCollection(collect($results));

        return new JsonResponse($products);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(int $id)
    {
        $seller = seller()->user();

        $sellerProduct = $this->productRepository->findOneWhere([
            'product_id'            => $id,
            'marketplace_seller_id' => $seller->id,
        ]);

        if (! $sellerProduct?->is_owner) {
            return redirect()->route('seller.products.assign.edit', $sellerProduct->id);
        } elseif ($sellerProduct) {
            $product = $this->baseProductRepository->with([
                'variants' => [
                    'inventories',
                ],
            ])->findOrFail($id);

            $sellerCategory = $this->sellerCategoryRepository->findOneWhere([
                'marketplace_seller_id' => $seller->id,
            ]);

            $categories = $this->categoryRepository
                ->whereIn('id', $sellerCategory?->categories ?: [])
                ->get()
                ->toTree();

            return view('marketplace::seller.products.edit')
                ->with([
                    'product'            => $product,
                    'sellerProduct'      => $sellerProduct,
                    'categories'         => $categories,
                    'inventorySources'   => core()->getCurrentChannel()->inventory_sources,
                    'productProgressBar' => $this->productProgressBar->getDetails($product),
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(ProductForm $request, int $id)
    {
        $seller = seller()->user();

        $sellerProducts = $this->productRepository->getMarketplaceProductByProduct($id, $seller);

        abort_if(! $sellerProducts, 401);

        request()->merge(['additional' => ['seller_id' => $seller->id]]);

        Event::dispatch('catalog.product.update.before', $id);

        $product = $this->baseProductRepository->update(request()->all(), $id);

        Event::dispatch('catalog.product.update.after', $product);

        $this->productRepository->update(request()->all(), $sellerProducts->id);

        session()->flash('success', trans('marketplace::app.seller.products.update-success'));

        return redirect()->route('seller.products.index');
    }

    /**
     * Uploads downloadable file.
     */
    public function uploadLink(int $id): JsonResponse
    {
        return new JsonResponse(
            $this->productDownloadableLinkRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Uploads downloadable sample file.
     */
    public function uploadSample(int $id): JsonResponse
    {
        return new JsonResponse(
            $this->productDownloadableSampleRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $product = $this->productRepository->find($id);

            if ($product->is_owner) {
                Event::dispatch('catalog.product.delete.before', $product->product_id);

                $this->baseProductRepository->delete($product->product_id);

                Event::dispatch('catalog.product.delete.after', $product->product_id);
            } else {
                $this->productRepository->delete($id);
            }

            return new JsonResponse([
                'message' => trans('marketplace::app.seller.products.delete-success'),
            ], 200);
        } catch (Exception $e) {
            report($e);

            return new JsonResponse([
                'message' => trans('marketplace::app.seller.products.delete-failed'),
            ], 500);
        }
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        try {
            foreach ($request->input('indices') as $id) {
                $product = $this->productRepository->find($id);

                if (! $product) {
                    continue;
                }

                if ($product->is_owner) {
                    Event::dispatch('catalog.product.delete.before', $product->product_id);

                    $this->baseProductRepository->delete($product->product_id);

                    Event::dispatch('catalog.product.delete.after', $product->product_id);
                } else {
                    $this->productRepository->delete($id);
                }
            }

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.products.index.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
