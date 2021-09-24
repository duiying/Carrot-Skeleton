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
     * @return mixed
     */
    public static function success($response, $data = [], $msg = 'success')
    {
        $formatData = [
            'code'  => 0,
            'msg'   => $msg,
            'data'  => $data
        ];

        $data = json_encode($formatData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $response->end($data);
    }
}