<?php

namespace App\Util;

use DuiYing\Logger;

class MySQLUtil
{
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
     * @return MySQLUtil
     */
    public function getConnection($host, $user, $pass, $db, $port = 3306, $charset = 'utf8mb4')
    {
        $this->conn = mysqli_connect($host, $user, $pass, $db, $port);
        mysqli_set_charset($this->conn, $charset);
        return $this;
    }

    /**
     * 断开连接
     */
    public function closeConnection()
    {
        $this->conn->close();
    }

    public function search(string $table, array $where = [], int $p = 0, int $size = 0, array $columns = ['*'], array $orderBy = [])
    {
        $sql = sprintf('SELECT %s FROM `%s` ', $this->buildColumn($columns), $table);
        $sql .= $this->buildWhere($where);
        $sql .= $this->buildOrderBy($orderBy);
        if ($p && $size) {
            $offset = ($p - 1) * $size;
            $sql .= sprintf('LIMIT %d,%d ', $offset, $size);
        }
        Logger::getInstance()->info("SQL", $sql);
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buildOrderBy($orderBy)
    {
        if (empty($orderBy)) return '';

        $str = '';

        foreach ($orderBy as $k => $v) {
            $str .= sprintf("`%s` %s,", $k, $v);
        }

        return 'ORDER BY ' . rtrim($str, ',') . ' ';
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

            if (!is_array($value)) {
                $whereStr .= sprintf("AND `%s` = '%s' ", $field, $value);
                continue;
            }

            switch ($value[0]) {
                case '=':   if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` = '%s' ", $field, $value[1]); break;
                case '!=':
                case '<>':  if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` <> '%s' ", $field, $value[1]); break;
                case '%':   if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` LIKE '%s' ", $field, $value[1]); break;
                case '<':   if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` < %d ", $field, $value[1]); break;
                case '<=':  if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` <= %d ", $field, $value[1]); break;
                case '>':   if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` > %d ", $field, $value[1]); break;
                case '>=':  if (!is_array($value[1]))   $whereStr .= sprintf("AND `%s` >= %d ", $field, $value[1]);; break;
                case '&':   if (is_array($value[1]))    $whereStr .= sprintf("AND `%s` >= %d AND `%s` <= %d ", $field, $value[1][0], $field, $value[1][1]); break;
                default:
                    $whereStr .= "AND `{$field}` IN (";
                    foreach ($value as $k => $v) {
                        $whereStr .= sprintf("'%s',", $v);
                    }
                    $whereStr = rtrim($whereStr, ',');
                    $whereStr .= ") ";
            }
        }

        return 'WHERE ' . ltrim(trim($whereStr), 'AND');
    }
}