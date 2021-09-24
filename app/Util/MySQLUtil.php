<?php

namespace App\Util;

use DuiYing\Logger;

class MySQLUtil
{
    const CODE_CONNECTION_FAIL      = 500;
    const CODE_CONNECTION_FAIL_MSG  = '连接数据库失败';

    /**
     * @var \mysqli
     */
    public $conn;

    /**
     * 获取本类实例化对象
     *
     * @return MySQLUtil
     */
    public static function getInstance()
    {
        return new self();
    }

    /**
     * 建立连接
     *
     * @param $host
     * @param $user
     * @param $pass
     * @param $db
     * @param int $port
     * @param string $charset
     * @return $this
     * @throws \Exception
     */
    public function getConnection($host, $user, $pass, $db, $port = 3306, $charset = 'utf8mb4')
    {
        $this->conn = mysqli_connect($host, $user, $pass, $db, $port);
        if ($this->conn === false) throw new \Exception(self::CODE_CONNECTION_FAIL_MSG, self::CODE_CONNECTION_FAIL);
        mysqli_set_charset($this->conn, $charset);
        $this->conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
        return $this;
    }

    /**
     * 断开连接
     */
    public function closeConnection()
    {
        $this->conn->close();
    }

    public function search(string $table, array $where = [], int $p = 1, int $size = 0, array $columns = ['*'], array $orderBy = [])
    {
        $sql = sprintf('SELECT %s FROM `%s`', $this->buildColumn($columns), $table);
        $sql .= $this->buildWhere($where);
        $sql .= $this->buildOrderBy($orderBy);
        if ($p && $size) {
            $offset = ($p - 1) * $size;
            $sql .= sprintf(' LIMIT %d,%d ', $offset, $size);
        }
        $sql = trim($sql);
        Logger::getInstance()->info("SQL", $sql);
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function count(string $table, array $where = [])
    {
        $sql = sprintf('SELECT count(*) `count` FROM `%s` ', $table);
        $sql .= $this->buildWhere($where);
        $sql = trim($sql);
        Logger::getInstance()->info("SQL", $sql);
        $result = $this->conn->query($sql);
        $count = $result->fetch_assoc()['count'];
        return $count ? intval($count) : 0;
    }

    public function find(string $table, array $where = [], array $columns = ['*'], array $orderBy = [])
    {
        $list = $this->search($table, $where, 1, 1, $columns, $orderBy);
        return $list ? (array)$list[0] : [];
    }

    public function create(string $table, array $data = [])
    {
        $keyList = array_keys($data);
        $valueList = array_values($data);

        $sql = sprintf('INSERT INTO `%s` (%s) VALUES ()', $table, $this->buildColumn($keyList));
    }

    public function buildOrderBy($orderBy)
    {
        if (empty($orderBy)) return '';

        $str = '';

        foreach ($orderBy as $k => $v) {
            $str .= sprintf("`%s` %s,", $k, $v);
        }

        return ' ORDER BY ' . rtrim($str, ',') . ' ';
    }

    public function buildColumn($columns)
    {
        $str = '';
        if (count($columns) === 1 && $columns[0] === '*') {
            return '*';
        } else {
            foreach ($columns as $k => $v) {
                $str .= sprintf("`%s`,", $v);
            }
        }
        return rtrim($str, ',');
    }

    public function buildWhere($where = [])
    {
        if (empty($where)) return '';

        $whereStr = '';

        foreach ($where as $field => $value) {
            if (is_null($value)) continue;

            // 转义
            if (is_string($value)) $value = $this->conn->real_escape_string($value);

            if (!is_array($value)) {
                $whereStr .= sprintf("AND `%s` = '%s' ", $field, $value);
                continue;
            }

            switch ($value[0]) {
                case '=':
                    if (!is_array($value[1])) {
                        // 转义
                        if (is_string($value[1])) $value[1] = $this->conn->real_escape_string($value[1]);
                        $whereStr .= sprintf("AND `%s` = '%s' ", $field, $value[1]);
                    }
                    break;
                case '!=':
                case '<>':
                    if (!is_array($value[1])) {
                        // 转义
                        if (is_string($value[1])) $value[1] = $this->conn->real_escape_string($value[1]);
                        $whereStr .= sprintf("AND `%s` <> '%s' ", $field, $value[1]);
                    }
                    break;
                case '%':   if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` LIKE '%s' ", $field, $value[1]); break;
                case '<':   if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` < %d ", $field, $value[1]); break;
                case '<=':  if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` <= %d ", $field, $value[1]); break;
                case '>':   if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` > %d ", $field, $value[1]); break;
                case '>=':  if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` >= %d ", $field, $value[1]);; break;
                case '&':   if (is_array($value[1]))    $whereStr .= sprintf("AND `%s` >= %d AND `%s` <= %d ", $field, $value[1][0], $field, $value[1][1]); break;
                default:
                    $whereStr .= "AND `{$field}` IN (";
                    foreach ($value as $k => $v) {
                        if (is_string($v)) {
                            $v = $this->conn->real_escape_string($v);
                        }
                        $whereStr .= sprintf("'%s',", $v);
                    }
                    $whereStr = rtrim($whereStr, ',');
                    $whereStr .= ") ";
            }
        }

        return ' WHERE' . ltrim(trim($whereStr), 'AND');
    }
}