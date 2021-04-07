<?php

declare(strict_types=1);

return [
    'mode' => SWOOLE_PROCESS,
    'http' => [
        'ip' => '0.0.0.0',
        'port' => 9502,
        'sock_type' => SWOOLE_SOCK_TCP,
        'settings' => [
            'worker_num' => swoole_cpu_num(),
            'daemonize' => true,
        ],
    ],
    'ws' => [
        'ip' => '0.0.0.0',
        'port' => 9512,
        'sock_type' => SWOOLE_SOCK_TCP,
        'settings' => [
            'worker_num' => swoole_cpu_num(),
            'open_websocket_protocol' => true,
        ],
    ],
];
