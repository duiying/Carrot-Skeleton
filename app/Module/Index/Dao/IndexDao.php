<?php

namespace App\Module\Index\Dao;

use Simps\DB\BaseModel;

class IndexDao extends BaseModel
{
    private $tableName = 'test';

    public function testDB()
    {
        $this->insert($this->tableName, []);
    }
}