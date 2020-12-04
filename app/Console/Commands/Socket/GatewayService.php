<?php

namespace App\Console\Commands\Socket;

use Illuminate\Console\Command;
use GatewayWorker\Gateway;
use Workerman\Worker;

class GatewayService extends Command
{
    use InitArgument;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:gateway {worker_command} {--mode=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'socket gateway service';

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
        if (config('socket.gateway_service', false)) {
            $gateway_socket_name = config('socket.gateway_socket_name');
            $register_address = config('socket.register_address', '127.0.0.1:1236');
            $gateway = new Gateway($gateway_socket_name);
            $gateway->name = 'Gateway';
            $gateway->count = config('socket.gateway_count', 1);
            $gateway->lanIp = config('socket.gateway_lan_ip', '127.0.0.1');
            $gateway->startPort = config('socket.gateway_start_port');
            $gateway->registerAddress = $register_address;
            if(!defined('GLOBAL_START')) {
                $path = storage_path('gateway/');
                file_exists($path) || @mkdir($path);
                Worker::$pidFile = $path . 'gateway.pid';
                Worker::runAll();
            }
        }
    }
}
