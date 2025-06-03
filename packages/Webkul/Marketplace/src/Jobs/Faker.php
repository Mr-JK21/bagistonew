<?php

namespace Webkul\Marketplace\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Marketplace\Helpers\Fakers\Faker as FakerHelper;

class Faker implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $entity,
        protected int $count
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(FakerHelper::class)->fake(
            $this->entity,
            $this->count,
        );
    }
}
