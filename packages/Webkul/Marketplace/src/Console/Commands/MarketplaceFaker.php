<?php

namespace Webkul\Marketplace\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Webkul\Marketplace\Jobs\Faker;

class MarketplaceFaker extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'marketplace:fake';

    /**
     * The console command description.
     */
    protected $description = 'Generates fake records for marketplace entities.';

    /**
     * The selected entity.
     */
    protected ?string $entity;

    /**
     * Number of records to create.
     */
    protected int $count;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->promptForEntity();

        if ($this->entity === 'products') {
            $this->info('Product functionality will be implemented later.');

            return;
        }

        $this->promptForLimit();

        $this->createBatches($this->entity, $this->count);
    }

    /**
     * Prompt for the entity to create.
     */
    protected function promptForEntity(): void
    {
        $choices = ['Sellers', 'Products'];

        $choice = $this->choice('Which record would you like to create?', $choices, 0);

        $this->entity = strtolower($choice);
    }

    /**
     * Prompt for the limit to create records.
     */
    protected function promptForLimit(): void
    {
        $count = $this->ask('Please enter the count for '.ucfirst($this->entity));

        if (
            ! is_numeric($count)
            || $count <= 0
        ) {
            $this->warn('Warning: Count must be a positive number.');

            $this->promptForLimit();

            return;
        }

        $this->count = (int) $count;
    }

    /**
     * Create selected records.
     */
    protected function createBatches(string $entity, int $count): void
    {
        $batch = Bus::batch([]);

        while ($count > 0) {
            $process = min($count, 10);

            $batch->add(new Faker($entity, $process));

            $count -= $process;
        }

        $batch->dispatch();
    }
}
