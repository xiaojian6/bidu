<?php

namespace App\Console\Commands\Socket;

use Illuminate\Console\Command;
use GatewayWorker\BusinessWorker;
use Workerman\Worker;

class BusinessService extends Command
{
    use InitArgument;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:business {worker_command} {--mode=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'socket business service';

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
        $this->initArgv();
        if (config('socket.business_service', false)) {
            $worker = new BusinessWorker();
            $worker->name = 'BusinessWorker';
            $worker->registerAddress = config('socket.register_address', '127.0.0.1:1236');
            $worker->count = config('socket.business_count');
            $worker->eventHandler = config('socket.business_event_handler', '');
            if(!defined('GLOBAL_START')) {
                $path = storage_path('gateway/');
                file_exists($path) || @mkdir($path);
                Worker::$pidFile = $path . 'business.pid';
                Worker::runAll();
            }
        }
    }
}
