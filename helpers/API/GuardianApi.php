<?php

namespace App\Helpers\API;


use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;


class GuardianApi implements ApiInterface
{
    public array $data = [];

    public array $formatted = [];

    private $api_key;

    private FetchInterface $fetch;

    private array $api_data_key;



    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('GUARDIAN_API_KEY');
        $this->fetch = $fetch;
        $this->api_data_key = [
            "webTitle",
            "webUrl",
            "fields",
            "pillarId",
            "pillarName",
            "sectionId",
            "sectionName"
        ];
    }

    public function headlines()
    {
        $url = "https://content.guardianapis.com/search?api-key=" . $this->api_key;
        $this->fetch->get($url);
        if ($this->fetch->response->status == "ok") {
            $this->data = $this->fetch->response->response->results;
        }
    }

    public function userFeed(string $type)
    {
        $url = "https://newsapi.org/v2/top-headlines?";
        $url .= $type;
        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->status == "ok") {
            $this->data = $this->fetch->response->response->results;
        }
    }

    public function search(string $search)
    {
        $url = "https://content.guardianapis.com/search?";
        $url .= "q=" . $search;
        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->status == "ok") {
            $this->data = $this->fetch->response->response->results;
        }
    }

    public function format(array $format_fields)
    {
        $formatted = [];

        foreach ($this->data as $index => $object) {
            foreach ($object as $key => $value) {
                if (!in_array($key, $this->api_data_key)) {
                    continue;
                }

                if ($key == 'fields' && count($value) > 0) {
                    $formatted[$index]['image'] = $value->thumbnail;
                } else {
                    $formatted[$index][$format_fields[array_search($key, $this->api_data_key)]] = $value;
                }
            }
        }

        $this->formatted = $formatted;
    }
}
