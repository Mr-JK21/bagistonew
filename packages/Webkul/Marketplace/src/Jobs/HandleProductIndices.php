<?php

namespace Webkul\Marketplace\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Marketplace\Helpers\Indexers\Product as ProductIndexerHelper;

class HandleProductIndices implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected ?object $seller,
        protected bool $reindex,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(ProductIndexerHelper::class)->createOrRemoveIndices($this->seller, $this->reindex);
    }
}
