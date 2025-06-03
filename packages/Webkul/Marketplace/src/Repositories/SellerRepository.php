<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\Seller;
use Webkul\Product\Repositories\ProductInventoryRepository;

class SellerRepository extends Repository
{
    /**
     * Create a new repository instance.
     */
    public function __construct(
        App $app,
        protected ProductInventoryRepository $productInventoryRepository
    ) {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return Seller::class;
    }

    /**
     * Retrieve seller from url
     */
    public function findByUrlOrFail(string $url)
    {
        if ($seller = $this->where('url', $url)->where('is_suspended', 0)->first()) {
            return $seller;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model),
            $url
        );
    }

    /**
     * create seller
     */
    public function create(array $data): object
    {
        $productTypes = collect(config('product_types'))->pluck('key')->toArray();

        Event::dispatch('marketplace.seller.create.before');

        $seller = parent::create(array_merge($data, [
            'is_approved'           => ! core()->getConfigData('marketplace.settings.seller.approval_required'),
            'allowed_product_types' => $productTypes,
        ]));

        $this->uploadImages($data, $seller, 'logo');

        $this->uploadImages($data, $seller, 'banner');

        $seller = $seller->refresh();

        Event::dispatch('marketplace.seller.create.after', $seller);

        return $seller;
    }

    /**
     * update seller
     */
    public function update(array $data, $id): object
    {
        Event::dispatch('marketplace.seller.update.before', $id);

        $seller = $this->findOrFail($id);

        parent::update($data, $id);

        $this->uploadImages($data, $seller, 'logo');

        $this->uploadImages($data, $seller, 'banner');

        $seller = $seller->refresh();

        Event::dispatch('marketplace.seller.update.after', $seller);

        return $seller;
    }

    /**
     * Upload seller images.
     */
    public function uploadImages(array $data, object $seller, string $type): void
    {
        if (isset($data[$type])) {
            foreach ($data[$type] as $file) {
                $dir = "seller/{$seller->id}";

                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    if ($seller->{$type}) {
                        Storage::delete($seller->{$type});
                    }

                    $seller->{$type} = $file->store($dir);
                    $seller->save();
                }
            }
        } else {
            if ($seller->{$type}) {
                Storage::delete($seller->{$type});
            }

            $seller->{$type} = null;
            $seller->save();
        }
    }

    /**
     * Delete seller
     */
    public function delete($id): void
    {
        Event::dispatch('marketplace.seller.delete.before', $id);

        parent::delete($id);

        Storage::deleteDirectory("seller/$id");

        $this->deleteInventory($id);

        Event::dispatch('marketplace.seller.delete.after', $id);
    }

    /**
     * Returns top sellers that have communication
     */
    public function getSellersForCommunication(array $params = []): object
    {
        return $this->getModel()
            ->with([
                'communication' => [
                    'messages',
                ],
            ])
            ->leftJoin('mp_communications', 'marketplace_sellers.id', '=', 'mp_communications.marketplace_seller_id')
            ->select('marketplace_sellers.*')
            ->whereNull('marketplace_sellers.parent_id')
            ->where(function ($query) {
                $query->whereNull('mp_communications.id')
                    ->orWhere('mp_communications.is_blocked', '=', 0);
            })
            ->when(isset($params['name']), function ($query) use ($params) {
                $query->where('marketplace_sellers.shop_title', 'LIKE', "%{$params['name']}%")
                    ->orWhere('marketplace_sellers.name', 'LIKE', "%{$params['name']}%")
                    ->orWhere('marketplace_sellers.email', 'LIKE', "%{$params['name']}%");
            })
            ->orderByRaw('mp_communications.id IS NOT NULL DESC')
            ->orderBy('mp_communications.updated_at', 'DESC')
            ->orderBy('marketplace_sellers.id', 'ASC')
            ->limit($params['limit'] ?? 20)
            ->get();
    }

    /**
     * Returns Featured Sellers
     */
    public function getFeaturedSellers(): object
    {
        $configPrefix = 'marketplace.settings.featured_sellers.';

        $limit = core()->getConfigData("{$configPrefix}limit_count") ?? 6;
        $criteria = core()->getConfigData("{$configPrefix}popularity_criteria") ?? 'maximum-orders';

        $criteriaColumns = [
            'maximum-orders'   => 'total_ordered',
            'maximum-products' => 'total_products',
            'maximum-rating'   => 'avg_rating',
            'maximum-sale'     => 'total_sale',
        ];

        $query = $this->getModel()
            ->leftJoin('marketplace_orders', 'marketplace_sellers.id', 'marketplace_orders.marketplace_seller_id')
            ->leftJoin('marketplace_products', 'marketplace_sellers.id', 'marketplace_products.marketplace_seller_id')
            ->leftJoin('marketplace_seller_reviews', function ($join) {
                $join->on('marketplace_seller_reviews.marketplace_seller_id', 'marketplace_sellers.id')
                    ->where('marketplace_seller_reviews.status', 'approved');
            })
            ->select(
                'marketplace_sellers.*',
                DB::raw('COUNT(DISTINCT marketplace_orders.id) as total_ordered'),
                DB::raw('COUNT(DISTINCT marketplace_products.id) as total_products'),
                DB::raw('AVG(marketplace_seller_reviews.rating) as avg_rating'),
                DB::raw('COUNT(DISTINCT marketplace_seller_reviews.id) as total_reviews'),
                DB::raw('SUM(marketplace_orders.base_grand_total) as total_sale'),
            )
            ->groupBy('marketplace_sellers.id')
            ->whereNotNull('marketplace_sellers.shop_title')
            ->whereNull('marketplace_sellers.parent_id')
            ->where('marketplace_sellers.is_approved', 1)
            ->where('marketplace_sellers.is_suspended', 0);

        if ($criteria === 'all') {
            $query->orderBy('total_ordered', 'DESC')
                ->orderBy('total_products', 'DESC')
                ->orderBy('avg_rating', 'DESC')
                ->orderBy('total_sale', 'DESC');
        } else {
            $orderByColumn = $criteriaColumns[$criteria] ?? 'total_ordered';

            $query->orderBy($orderByColumn, 'DESC');
        }

        return $query->limit($limit)->get();
    }

    /**
     * Delete seller inventories
     */
    public function deleteInventory(int $id): void
    {
        $this->productInventoryRepository->getModel()
            ->where('vendor_id', $id)
            ->delete();
    }

    /**
     * Returns seller allowed Product Types
     *
     * @return object
     */
    public function getAllowedProducts($seller = null)
    {
        $seller = $seller ?? seller()->user();

        return collect(config('product_types'))->only($seller->allowed_product_types ?: []);
    }

    /**
     * Count sellers with all access.
     */
    public function countSellersWithAllAccess(object $seller): int
    {
        return $this->getModel()
            ->leftJoin('marketplace_roles', 'marketplace_sellers.marketplace_role_id', '=', 'marketplace_roles.id')
            ->where('marketplace_roles.permission_type', 'all')
            ->where('marketplace_roles.marketplace_seller_id', $seller->id)
            ->count();
    }
}
