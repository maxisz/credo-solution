<?php

namespace App\Helpers;


class tinyPesa
{
    protected $url = 'https://tinypesa.com/api/v1/express/initialize';
    protected $status_url ='https://tinypesa.com/api/v1/express/get_status/';
    protected $api_key;

    public function __construct($key)
    {
        $this->api_key = $key;
    }

    public function initiate($phone,$amount,$account_id)
    {

      
        $this->account_id = base64_encode($account_id.','.$phone.','.date('h:i:s:m'));

        



        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Content-Type: application/x-www-form-urlencoded",
        "ApiKey: ".$this->api_key,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = "amount=".$amount."&msisdn=".$phone."&account_no=".$this->account_id;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        $resp->account_id = $this->account_id;
        $this->account_id;
        return $resp;
    }

    public function status($id)
    {
        $curl = curl_init($this->status_url.$id);
        curl_setopt($curl, CURLOPT_URL, $this->status_url.$id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Accept: application/json",
        "Content-Type: application/x-www-form-urlencoded",
        "ApiKey: ".$this->api_key,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        // var_dump($resp);
        return $resp;

    }

}