<?php

namespace Webkul\Marketplace\Console\Commands;

use Illuminate\Console\Command;

class MarketplaceVersion extends Command
{
    /**
     * Marketplace package version.
     */
    private const VERSION = '2.2.2';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marketplace:version';

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
        $this->description = trans('marketplace::app.commands.version.description');

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->comment(trans('marketplace::app.commands.version.comment', ['version' => self::VERSION]));
    }
}
