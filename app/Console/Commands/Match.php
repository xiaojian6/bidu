<?php

namespace App\Console\Commands;

use App\Console\Commands\Socket\InitArgument;
use App\DAO\MatchEngine;
use App\Models\CurrencyMatch;
use Illuminate\Console\Command;
use Workerman\Worker;

class Match extends Command
{
    use InitArgument;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'match {worker_command} {--mode=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '撮合引擎';


    /**
     * @var array
     */
    public static $currency_match_ids;


    /**
     * @var MatchEngine|null
     */
    protected $matchEngine = null;


    /**
     * @var Worker|null
     */
    protected $worker = null;

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
        $this->matchEngine = new MatchEngine();

        self::$currency_match_ids = CurrencyMatch::where('open_transaction', 1)->get()
            ->pluck('id')->all();

        $this->worker = new Worker();
        $this->worker->count = count(self::$currency_match_ids);
        $this->worker->name = 'Match Engine Main';

        $this->bindEvent();

        $path = storage_path('/match_engine/');
        file_exists($path) || @mkdir($path);
        Worker::$pidFile = $path . 'match_engine.pid';;

        Worker::runAll();
    }

    protected function bindEvent()
    {
        $events = [
            'onWorkerStart',
            'onConnect',
            'onMessage',
            'onClose',
            'onError',
            'onBufferFull',
            'onBufferDrain',
            'onWorkerStop',
            'onWorkerReload'
        ];

        foreach ($events as $key => $event) {
            method_exists($this->matchEngine, $event) && $this->worker->$event = [$this->matchEngine, $event];
        }
    }
}
