<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Worker;;
use App\Console\Commands\Socket\InitArgument;
use Illuminate\Support\Facades\Artisan;

class Socket extends Command
{
    use InitArgument;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket {worker_command} {--mode=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Workerman Gateway';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        define('GLOBAL_START', true);
        $this->initArgv();
    }

    protected function config()
    {
        
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init();
        $path = storage_path('gateway/');
        file_exists($path) || @mkdir($path);
        $worker_command = $this->argument('worker_command');
        if ($worker_command == 'start') {
            file_exists($path . 'register.pid') || Artisan::call('socket:register', ['worker_command' => $worker_command]);
            file_exists($path . 'gateway.pid') || Artisan::call('socket:gateway', ['worker_command' => $worker_command]);
            file_exists($path . 'business.pid') || Artisan::call('socket:business', ['worker_command' => $worker_command]);
            file_exists($path . 'channel.pid') || Artisan::call('socket:channel', ['worker_command' => $worker_command]);
        } else {
            Artisan::call('socket:register', ['worker_command' => $worker_command]);
            Artisan::call('socket:gateway', ['worker_command' => $worker_command]);
            Artisan::call('socket:business', ['worker_command' => $worker_command]);
            Artisan::call('socket:channel', ['worker_command' => $worker_command]);
        }
        Worker::$pidFile = $path . 'socket.pid';
        Worker::runAll();
    }
}
