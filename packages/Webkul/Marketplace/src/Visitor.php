<?php

namespace Webkul\Marketplace;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Shetabit\Visitor\Visitor as BaseVisitor;
use Webkul\Core\Jobs\UpdateCreateVisitIndex;
use Webkul\Marketplace\Models\Catalog\Product;
use Webkul\Marketplace\Models\Seller;

class Visitor extends BaseVisitor
{
    /**
     * Search engine.
     */
    protected ?object $seller = null;

    /**
     * Create a new Visitor instance.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request, config('visitor'));
    }

    /**
     * Create a visit log.
     */
    public function visit(?Model $model = null)
    {
        foreach ($this->except as $path) {
            if ($this->request->is($path)) {
                return;
            }
        }

        $visitLog = $this->getLog();

        if ($model) {
            $visitLog = array_merge($visitLog, [
                'visitable_id'   => $model->id,
                'visitable_type' => $this->getVisitableType($model),
            ]);
        }

        UpdateCreateVisitIndex::dispatch(null, $visitLog);
    }

    /**
     * Retrieve request's url
     */
    public function url(): string
    {
        return $this->request->url();
    }

    /**
     * Prepare log's data.
     */
    protected function prepareLog(): array
    {
        return array_merge(parent::prepareLog(), [
            'channel_id'            => core()->getCurrentChannel()->id,
            'marketplace_seller_id' => $this->seller?->id,
        ]);
    }

    /**
     * Returns logs
     */
    public function getLog(): array
    {
        return $this->prepareLog();
    }

    /**
     * Copy product.
     */
    public function setSeller(Seller $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get visitable type.
     */
    private function getVisitableType(Model $model): string
    {
        if ($model instanceof Product) {
            return get_parent_class($model);
        }

        return get_class($model);
    }
}
