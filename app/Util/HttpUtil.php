<?php

namespace App\Util;

class HttpUtil
{
    /**
     * HTTP 成功响应数据
     *
     * @param $response
     * @param array $data
     * @param string $msg
     * @return false|string
     */
    public static function success($response, $data = [], $msg = 'success')
    {
        $response->end(json_encode(
            [
                'code'  => 0,
                'msg'   => $msg,
                'data'  => $data
            ]
        ));
    }
}