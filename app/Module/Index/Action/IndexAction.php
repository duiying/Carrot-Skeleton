<?php

namespace App\Module\Index\Action;

use App\Constant\TaskConstant;
use App\Module\Index\Logic\IndexLogic;
use App\Util\HttpUtil;
use Carrot\Lib\Logger;
use Carrot\Server\HttpServer;

class IndexAction
{
    public function index($request, $response)
    {
        // 投递一个测试 Task
        HttpServer::deliveryTask(TaskConstant::TASK_NAME_TEST, ['key' => 'val']);

        $logger = Logger::getInstance();

        $data = [
            'method' => $request->server['request_method'],
            'message' => 'Hello Carrot',
        ];

        $logger->info('index 方法打印日志', $data);

        $response->end(HttpUtil::success($data));
    }

    public function testDB($request, $response)
    {
        $logic = IndexLogic::getInstance();
    }
}