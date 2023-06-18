<?php

namespace App\Helpers\API;


use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;


class GuardianApi implements ApiInterface
{

    private $api_key;

    private FetchInterface $fetch;

    public object $data;


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('GUARDIAN_API_KEY');
        $this->fetch = $fetch;
    }

    public function headlines()
    {
        $url = "https://content.guardianapis.com/search?api-key=" . $this->api_key;
        $this->fetch->get($url);
        $this->data = $this->fetch->response;
    }

    public function userFeed(string $type)
    {
        $url = "https://newsapi.org/v2/top-headlines?";
        $url .= $type;
        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        $this->data = $this->fetch->response;
    }

    public function search(string $search)
    {
        $url = "https://content.guardianapis.com/search?";
        $url .= "q=" . $search;
        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        $this->data = $this->fetch->response;
    }
}
