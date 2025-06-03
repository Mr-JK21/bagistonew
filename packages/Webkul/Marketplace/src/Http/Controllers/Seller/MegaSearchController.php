<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\JsonResponse;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\ProductRepository;

class MegaSearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Search Products.
     *
     * @return JsonResponse
     */
    public function products()
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

        return response()->json($products);
    }

    /**
     * Search Orders.
     *
     * @return JsonResponse
     */
    public function orders()
    {
        $orders = $this->orderRepository->searchOrders(seller()->user());

        foreach ($orders as $key => $order) {
            $orders[$key]['formatted_created_at'] = core()->formatDate($order->created_at, 'd M Y');

            $orders[$key]['status_label'] = $order->status_label;

            $orders[$key]['customer_full_name'] = $order->customer_first_name.' '.$order->customer_last_name;
        }

        return response()->json($orders);
    }

    /**
     * Search customers.
     *
     * @return JsonResponse
     */
    public function customers()
    {
        $customers = $this->orderRepository->searchCustomers(seller()->user());

        return response()->json($customers);
    }
}
