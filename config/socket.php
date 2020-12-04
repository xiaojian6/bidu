<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Socket注册服务是否开启
    |--------------------------------------------------------------------------
    |
    | 请确保只有一台服务器开启了Socket注册服务，注册服务用于统一管理分布式服务器资源
    |
    */

    'register_service' => env('SOCKET_REGISTER_SERVICE', false),

    'register_address' => env('SOCKET_REGISTER_ADDRESS', '127.0.0.1:1236'),

    /*
    |--------------------------------------------------------------------------
    | Socket分布式服务是否开启
    |--------------------------------------------------------------------------
    |
    | 建议开启，以提高Socket服务性能
    |
    */

    'gateway_service' => env('SOCKET_GATEWAY_SERVICE', true),

    'gateway_socket_name' => env('SOCKET_GATEWAY_SOCKET_NAME', 'Websocket://127.0.0.1:7272'),

    'gateway_count' => env('SOCKET_GATEWAY_COUNT', 1),

    'gateway_lan_ip' => env('SOCKET_GATEWAY_LAN_IP', '127.0.0.1'),

    'gateway_start_port' => env('SOCKET_GATEWAY_START_PORT', 2000),

    /*
    |--------------------------------------------------------------------------
    | Socket业务逻辑处理服务是否开启
    |--------------------------------------------------------------------------
    |
    | 建议开启，以提高业务逻辑的处理性能
    |
    */

    'business_service' => env('SOCKET_BUSINESS_SERVICE', true),

    'business_count' => env('SOCKET_BUSINESS_COUNT', 1),

    'business_event_handler' => \App\Events\Gateway\EventsHandler::class,

    /*
    |--------------------------------------------------------------------------
    | Socket Channel分布式通讯服务是否开启
    |--------------------------------------------------------------------------
    |
    | 建议开启，以提高业务逻辑的处理性能
    |
    */
    'channel_service' =>  env('SOCKET_CHANNEL_SERVICE', false),
    'channel_ip' =>  env('SOCKET_CHANNEL_IP', '127.0.0.1'),
    'channel_port' =>  env('SOCKET_CHANNEL_PORT', '2206'),
];
