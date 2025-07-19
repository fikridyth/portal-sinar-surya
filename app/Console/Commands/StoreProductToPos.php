<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProductController;
use Illuminate\Console\Command;

class StoreProductToPos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:store-product-to-pos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store or update product from portal to POS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schedule = new ProductController;
        $schedule->storeToPos();
    }
}
