<?php

return [
    'hosts' => explode(',', env('ELASTICSEARCH_HOST', '')),
    'index' => env('ELASTICSEARCH_INDEX', 'market.kline'),
];
