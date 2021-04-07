<?php

declare(strict_types=1);

return [
    ['GET', '/', '\App\Module\Index\IndexAction@index'],
    ['GET', '/favicon.ico', function ($request, $response) {
        $response->end('');
    }],
];
