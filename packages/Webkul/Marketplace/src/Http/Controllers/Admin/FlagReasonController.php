<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Core\Repositories\LocaleRepository;
use Webkul\Marketplace\DataGrids\Admin\FlagReasonDataGrid;
use Webkul\Marketplace\Repositories\FlagReasonRepository;

class FlagReasonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected FlagReasonRepository $flagReasonRepository,
        protected LocaleRepository $localeRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(FlagReasonDataGrid::class)->process();
        }

        return view('marketplace::admin.flag-reasons.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $locales = $this->localeRepository->all(['code', 'name']);

        return view('marketplace::admin.flag-reasons.create')
            ->with('locales', $locales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): RedirectResponse
    {
        $this->validate(request(), [
            'admin_name' => 'required',
            'type'       => 'required',
        ]);

        Event::dispatch('admin.marketplace.flag-reason.create.before');

        $flagReason = $this->flagReasonRepository->create(request()->all());

        Event::dispatch('admin.marketplace.flag-reason.create.after', $flagReason);

        return to_route('admin.marketplace.flag_reasons.index')
            ->with('success', trans('marketplace::app.admin.flag-reasons.create-success'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $flagReason = $this->flagReasonRepository->findOrFail($id);

        $locales = $this->localeRepository->all(['code', 'name']);

        return view('marketplace::admin.flag-reasons.edit')
            ->with([
                'flagReason' => $flagReason,
                'locales'    => $locales,
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): RedirectResponse
    {
        $this->validate(request(), [
            'admin_name' => 'required',
            'type'       => 'required',
        ]);

        $flagReason = $this->flagReasonRepository->findOrFail($id);

        Event::dispatch('admin.marketplace.flag-reason.update.before', $flagReason);

        $flagReason = $this->flagReasonRepository->update(request()->all(), $id);

        Event::dispatch('admin.marketplace.flag-reason.update.after', $flagReason);

        return to_route('admin.marketplace.flag_reasons.index')
            ->with('success', trans('marketplace::app.admin.flag-reasons.update-success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            Event::dispatch('admin.marketplace.flag-reason.delete.before', $id);

            $this->flagReasonRepository->delete($id);

            Event::dispatch('admin.marketplace.flag-reason.delete.after', $id);

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.flag-reasons.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        foreach ($request->input('indices') as $id) {
            $flagReason = $this->flagReasonRepository->find($id);

            if (isset($flagReason)) {
                Event::dispatch('admin.marketplace.flag-reason.delete.before', $id);

                $flagReason->delete();

                Event::dispatch('admin.marketplace.flag-reason.delete.after', $id);
            }
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.flag-reasons.index.mass-delete-success'),
        ], 200);
    }

    /**
     * Mass delete the specified resources.
     */
    public function massUpdate(MassUpdateRequest $request): JsonResponse
    {
        foreach ($request->input('indices') as $id) {
            $flagReason = $this->flagReasonRepository->find($id);

            if (isset($flagReason)) {
                Event::dispatch('admin.marketplace.flag-reason.update.before', $flagReason);

                $flagReason = $this->flagReasonRepository->update([
                    'status' => $request->input('value'),
                ], $id);

                Event::dispatch('admin.marketplace.flag-reason.update.after', $flagReason);
            }
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.flag-reasons.index.mass-update-success'),
        ], 200);
    }
}
