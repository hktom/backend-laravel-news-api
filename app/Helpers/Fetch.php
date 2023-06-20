<?php

namespace App\Helpers;

use App\Helpers\Interfaces\FetchInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;

class Fetch implements FetchInterface
{
    public $response = [];
    public $responses = [];
    private $urls = [];
    // private $curl;

    public function __construct()
    {
        // $this->curl = curl_init();
    }

    public function pushUrls(array $urls)
    {
        $this->urls = $urls;
    }

    // private function pushUrlToPool(Pool $pool): array
    // {
    //     // $data = [];
    //     // foreach ($this->urls as $key => $url) {
    //     //     $data[] = $pool->as($key)->get($url);
    //     // }
    //     // return $data;
    //     return [];
    // }

    public function getHttp()
    {
        $urls = $this->urls;
        $responses = Http::pool(function ($pool) use ($urls) {
            $handles = array();
            foreach ($urls as $key => $url) {
                $handles[] = $pool->as($key)->get($url);
            }
            return $handles;
        });

        foreach ($this->urls as $key => $url) {
            $this->responses[$key] = $responses[$key]->object();
        }

        // $responses = Http::pool(fn (Pool $pool) => $this->pushUrlToPool($pool));
        // foreach ($this->urls as $key => $url) {
        //     $this->responses[$key] = $responses[$key]->object();
        // }
    }

    // public function get(string $url)
    // {
    //     try {
    //         curl_setopt_array($this->curl, array(
    //             CURLOPT_URL => $url,
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => '',
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 0,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => 'GET',
    //         ));

    //         // set user agent
    //         curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0');

    //         $response = curl_exec($this->curl);

    //         // curl_close($this->curl);

    //         $this->response = json_decode($response);
    //     } catch (\Throwable $th) {
    //         $this->response = [];
    //     }
    // }

    public function close()
    {
        $this->urls = [];
        // curl_close($this->curl);
    }
}
