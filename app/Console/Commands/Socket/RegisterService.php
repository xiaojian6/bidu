<?php

namespace App\Console\Commands\Socket;

use Illuminate\Console\Command;
use GatewayWorker\Register;
use Workerman\Worker;

class RegisterService extends Command
{
    use InitArgument;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:register {worker_command} {--mode=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'socket register service';

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
        if (config('socket.register_service', false)) {
            $register_address = config('socket.register_address', '127.0.0.1:1236');
            $register = new Register('Text://' . $register_address);
            if(!defined('GLOBAL_START')) {
                $path = storage_path('gateway/');
                file_exists($path) || @mkdir($path);
                Worker::$pidFile = $path . 'register.pid';
                Worker::runAll();
            }
        }
    }
}
