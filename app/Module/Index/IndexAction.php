<?php

namespace App\Module\Index;

use App\Constant\TaskConstant;
use Carrot\Server\HttpServer;

class IndexAction
{
    public function index($request, $response)
    {
        echo 333;
        // 投递一个测试 Task
        HttpServer::deliveryTask(TaskConstant::TASK_NAME_TEST, ['key' => 'val']);
echo 444;
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