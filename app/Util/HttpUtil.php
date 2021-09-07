<?php

namespace App\Util;

class HttpUtil
{
    /**
     * HTTP 成功响应数据
     *
     * @param array $data
     * @param string $msg
     * @return false|string
     */
    public static function success($data = [], $msg = '')
    {
        return json_encode(
            [
                'code'  => 0,
                'msg'   => $msg,
                'data'  => $data
            ]
        );
    }
}