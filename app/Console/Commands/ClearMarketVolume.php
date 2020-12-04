<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CurrencyQuotation;

class ClearMarketVolume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:clear:volume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除24小时成交量';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        CurrencyQuotation::whereRaw(true)->update(['volume'=> 0]);
    }
}
