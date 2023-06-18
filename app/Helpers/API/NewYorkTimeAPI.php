<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;

class NewYorkTimeApi implements ApiInterface
{

    public array $data = [];

    public array $formatted = [];

    private $api_key;

    private FetchInterface $fetch;

    private array $api_data_key;


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('NEW_YORK_TIME_API_KEY');
        $this->fetch = $fetch;
        $this->api_data_key = [
            'headline',
            'abstract',
            'web_url',
            'lead_paragraph',
            'multimedia',
            'source',
            'pub_date',
            'news_desk',
            'section_name',
        ];
    }

    public function headlines()
    {
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?api-key=" . $this->api_key;
        $this->fetch->get($url);
        if ($this->fetch->response->status == "OK") {
            $this->data = $this->fetch->response->response->docs;
        }
    }

    public function userFeed(string $type)
    {
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?";
        $url .= $type;
        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->status == "OK") {
            $this->data = $this->fetch->response->response->docs;
        }
    }

    public function search(string $search)
    {
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?";
        $url .= "q=" . $search;
        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->status == "OK") {
            $this->data = $this->fetch->response->response->docs;
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

                if ($key == 'multimedia' && count($value) > 0) {
                    $formatted[$index]['image'] = $value[0]->url;
                } else if ($key == 'headline' && is_object($value)) {
                    $formatted[$index]['title'] = $value->main;
                } else {
                    $formatted[$index][$format_fields[array_search($key, $this->api_data_key)]] = $value;
                }
            }
        }

        $this->formatted = $formatted;
    }
}
