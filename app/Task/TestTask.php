<?php

namespace App\Task;

use Carrot\Singleton;

class TestTask
{
    use Singleton;

    public function test($data = [])
    {
        sleep(5);
    }
}