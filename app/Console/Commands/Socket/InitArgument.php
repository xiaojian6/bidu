<?php

namespace App\Console\Commands\Socket;

trait InitArgument
{
    public function initArgv()
    {
        global $argv;
        $argv[1] = $command = $this->argument('worker_command');
        $mode = $this->option('mode');
        isset($mode) && $argv[2] = '-' . $mode;
    }
}
