<?php

namespace App\Module\Index;

use App\Constant\TaskConstant;
use Carrot\Server\HttpServer;

class IndexAction
{
    public function index($request, $response)
    {
        // 投递一个测试 Task
        HttpServer::deliveryTask(TaskConstant::TASK_NAME_TEST, ['key' => 'val']);

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