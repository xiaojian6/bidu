<?php

return [
    'socket_io_port' => env('SOCKET_IO_PORT'),
    
    'http_worker_port' => env('HTTP_WORKER_PORT', ''),
    
    'web_server_port' => env('WEB_SERVER_PORT', ''),

    'text_worker_port' => env('TEXT_WORKER_PORT', ''),

    'worker_push_url' => env('WORKER_PUSH_URL', ''),
];
