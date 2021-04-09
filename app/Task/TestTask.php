<?php

namespace App\Task;

use Carrot\Singleton;

class TestTask
{
    use Singleton;

    public function test($data = [])
    {
        echo '1111';
        sleep(5);
        echo 222;
    }
}