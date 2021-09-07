<?php

declare(strict_types=1);

return [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'app',
    'username' => 'root',
    'password' => 'ksxyh@2GDN2',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
    // 连接池 size
    'size' => 64,
];
