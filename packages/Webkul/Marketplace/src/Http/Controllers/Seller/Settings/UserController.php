<?php

namespace Webkul\Marketplace\Http\Controllers\Seller\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Webkul\Marketplace\DataGrids\Seller\UserDataGrid;
use Webkul\Marketplace\Http\Controllers\Seller\Controller;
use Webkul\Marketplace\Http\Requests\UserFormRequest;
use Webkul\Marketplace\Repositories\RoleRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected RoleRepository $roleRepository,
        protected SellerRepository $sellerRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(UserDataGrid::class)->process();
        }

        $roles = $this->roleRepository
            ->where('marketplace_seller_id', seller()->user()->id)
            ->get(['id', 'name']);

        return view('marketplace::seller.settings.users.index')
            ->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserFormRequest $request): JsonResponse
    {
        Event::dispatch('marketplace.seller.user.create.before');

        $parent = seller()->user();

        $seller = $this->sellerRepository->create(array_merge($request->validated(), [
            'is_approved'           => ! core()->getConfigData('marketplace.settings.seller.approval_required'),
            'parent_id'             => $parent->id,
            'allowed_product_types' => $parent->allowed_product_types,
        ]));

        Event::dispatch('marketplace.seller.user.create.after', $seller);

        return new JsonResponse([
            'message' => trans('marketplace::app.seller.settings.users.index.create-success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $seller = $this->sellerRepository->findOrFail($id);

        return new JsonResponse($seller);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserFormRequest $request): JsonResponse
    {
        Event::dispatch('marketplace.seller.user.update.before', request()->id);

        $data = $request->validated();

        if (empty($data['password'])) {
            $data = Arr::except($data, 'password');
        }

        $seller = $this->sellerRepository->update($data, request()->id);

        Event::dispatch('marketplace.seller.user.update.after', $seller);

        return new JsonResponse([
            'message' => trans('marketplace::app.seller.settings.users.index.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            Event::dispatch('marketplace.seller.user.delete.before', $id);

            $this->sellerRepository->deleteWhere([
                'id'        => $id,
                'parent_id' => seller()->user()->id,
            ]);

            Event::dispatch('marketplace.seller.user.delete.after', $id);

            return new JsonResponse([
                'message' => trans('marketplace::app.seller.settings.users.index.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('marketplace::app.seller.settings.users.index.delete-failed'),
            ], 500);
        }
    }
}
