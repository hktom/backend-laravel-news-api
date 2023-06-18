<?php

namespace App\Helpers;

class Fetch
{
    public $response = [];

    public function __construct()
    {
    }

    public function get(string $url)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            // set user agent
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0');

            $response = curl_exec($curl);

            curl_close($curl);

            $this->response = json_decode($response);

            
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
