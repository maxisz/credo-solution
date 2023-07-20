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
        return $response;
    }
    

}
