<?php

namespace App\Constant;

class TaskConstant
{
    // 测试 Task 名称
    const TASK_NAME_TEST = 'test';

    // （名称 - 执行类 & 执行方法） Map 关系
    const TASK_MAP = [
        self::TASK_NAME_TEST => '\App\Task\TestTask@test',
    ];
}