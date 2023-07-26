<?php

namespace App\Helpers;


class AppHelper
{

    public static function resp($status, $code, $data)
    {
        $response = [
            'status' => $status,
            'code' => $code,
            'content' =>
            // "code" => $code,
            $data,
        ];
        return new \Illuminate\Http\Response($response, 200, ['Access-Control-Allow-Origin' => '*']);
    }


}