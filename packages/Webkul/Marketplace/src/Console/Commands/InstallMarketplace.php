<?php

namespace Webkul\Marketplace\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Marketplace\Database\Seeders\DatabaseSeeder;
use Webkul\Marketplace\Providers\MarketplaceServiceProvider;

class InstallMarketplace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marketplace:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->description = trans('marketplace::app.commands.install.description');

        parent::__construct();
    }

    /**
     * Install and configure Marketplace.
     */
    public function handle()
    {
        $this->warn(trans('marketplace::app.commands.install.migrate'));
        $this->call('migrate');

        $this->warn(trans('marketplace::app.commands.install.seed'));
        $this->info(app(DatabaseSeeder::class)->run());

        $this->warn(trans('marketplace::app.commands.install.publish'));
        $this->info($this->call('vendor:publish', [
            '--provider' => MarketplaceServiceProvider::class,
            '--force'    => true,
        ]));

        $this->warn(trans('marketplace::app.commands.install.cache'));
        $this->call('optimize:clear');

        $this->comment(trans('marketplace::app.commands.install.finish'));
    }
}
