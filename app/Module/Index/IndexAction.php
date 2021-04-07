<?php

namespace App\Module\Index;

class IndexAction
{
    public function index($request, $response)
    {
        $response->end(
            json_encode(
                [
                    'method' => $request->server['request_method'],
                    'message' => 'Hello Carrot',
                ]
            )
        );
    }
}