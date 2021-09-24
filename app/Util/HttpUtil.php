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
    public static function success($data = [], $msg = 'success')
    {
        $formatData = [
            'code'  => 0,
            'msg'   => $msg,
            'data'  => $data
        ];

        return json_encode($formatData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}