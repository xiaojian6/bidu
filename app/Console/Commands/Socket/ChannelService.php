<?php

namespace App\Console\Commands\Socket;

use Illuminate\Console\Command;
use Channel\Server;
use Workerman\Worker;

class ChannelService extends Command
{
    use InitArgument;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:channel {worker_command} {--mode=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'socket channel service';

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
        if (config('socket.channel_service', false)) {
            $channel_server = new Server('0.0.0.0', config('socket.channel_port', 2206));
            if (!defined('GLOBAL_START')) {
                $path = storage_path('gateway/');
                file_exists($path) || @mkdir($path);
                Worker::$pidFile = $path . 'channel.pid';
                Worker::runAll();
            }
        }
    }
}
