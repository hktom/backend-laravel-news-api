<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;

class NewsApi implements ApiInterface
{

    private $api_key;

    private FetchInterface $fetch;

    private array $api_data_key;

    public array $data = [];

    public array $formatted = [];


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('NEWS_API_KEY');
        $this->fetch = $fetch;

        $this->api_data_key =  [
            'title',
            'description',
            'content',
            'urlToImage',
            'url',
            'publishedAt',
            'source',
            'author',
            'category'
        ];
        // $this->field = ['title', 'description', 'content', 'image', 'url', 'publishedAt', 'source', 'author_name', 'category_name'];
        // $this->reducer = $reducer;
    }

    public function headlines()
    {
        $url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=" . $this->api_key;
        $this->fetch->get($url);
        if ($this->fetch->status == "ok") {
            $this->data = $this->fetch->articles;
        }
    }

    public function userFeed(string $type)
    {
        $url = "https://newsapi.org/v2/top-headlines?";
        $url .= $type;
        $url .= "&apiKey=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->status == "ok") {
            $this->data = $this->fetch->articles;
        }
    }

    public function search(string $search)
    {
        $url = "https://newsapi.org/v2/everything?";
        $url .= "q=" . $search;
        $url .= "&apiKey=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->status == "ok") {
            $this->data = $this->fetch->articles;
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
                if (!is_object($value)) {
                    $formatted[$index][$format_fields[array_search($key, $this->api_data_key)]] = $value;
                } else {
                    foreach ($value as $key3 => $value3) {
                        $formatted[$index][$format_fields[array_search($key, $this->api_data_key)] . "_" . $key3] = $value3;
                    }
                }
            }
        }

        $this->formatted = $formatted;
    }
}
