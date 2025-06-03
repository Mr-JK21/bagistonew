<?php

namespace Webkul\Marketplace\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Marketplace\Jobs\HandleProductIndices as HandleProductIndicesJob;

class Configuration
{
    /**
     * After marketplace configuration update
     */
    public function afterUpdate()
    {
        if (
            request()->route()->getName() != 'admin.configuration.store'
            || ! $data = request()->get('marketplace')
        ) {
            return;
        }

        ResponseCache::forget('marketplace');

        try {
            HandleProductIndicesJob::dispatch(null, $data['settings']['general']['status']);
        } catch (\Exception $e) {
        }
    }
}
