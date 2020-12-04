<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\LeverTransaction;
use App\Models\UsersWallet;
use App\Models\AccountLog;
use GuzzleHttp\Client;

class PhpRobot extends Command
{
    protected $signature = 'php_robot {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PHP机器人';

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
        
    }
}