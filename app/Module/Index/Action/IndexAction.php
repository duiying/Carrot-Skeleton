<?php

namespace App\Module\Index\Action;

use App\Constant\TaskConstant;
use App\Module\Index\Logic\IndexLogic;
use App\Util\MySQLUtil;
use Carrot\HttpUtil;
use Carrot\Server\HttpServer;
use Carrot\Singleton;
use DuiYing\Logger;

class IndexAction
{
    use Singleton;

    public function index($request, $response)
    {
        // 投递一个测试 Task
        // HttpServer::deliveryTask(TaskConstant::TASK_NAME_TEST, ['key' => 'val']);

        $db = MySQLUtil::getInstance()->getConnection('127.0.0.1', 'root', 'WYX*wyx123', 'account');
        $table = 't_user';
        $user = $db->find($table, ['id' => 1, 'name' => "duiying'"]);
        $del = $db->delete($table, ['id' => ['>', 4]]);

        return $response->end(HttpUtil::success(['user' => $user, 'del' => $del]));
    }
}