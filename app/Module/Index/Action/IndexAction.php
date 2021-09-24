<?php

namespace App\Module\Index\Action;

use App\Constant\TaskConstant;
use App\Module\Index\Logic\IndexLogic;
use App\Util\HttpUtil;
use App\Util\MySQLUtil;
use Carrot\Server\HttpServer;
use DuiYing\Logger;

class IndexAction
{
    public function index($request, $response)
    {
        // 投递一个测试 Task
        // HttpServer::deliveryTask(TaskConstant::TASK_NAME_TEST, ['key' => 'val']);

        $db = MySQLUtil::getInstance()->getConnection('127.0.0.1', 'root', 'WYX*wyx123', 'account');

        $list = $db->search('t_user');

        return $response->end(HttpUtil::success(['list' => $list]));
    }
}