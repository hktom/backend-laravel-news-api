<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;
use App\Helpers\Interfaces\ApiFormatterInterface;

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

        // $this->api_data_key =  [
        //     'title',
        //     'description',
        //     'content',
        //     'urlToImage',
        //     'url',
        //     'publishedAt',
        //     'source',
        //     'author',
        //     'category'
        // ];

    }

    public function headlines()
    {
        $url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=" . $this->api_key;
        $this->fetch->get($url);
        if ($this->fetch->response->status == "ok") {
            $this->data = $this->fetch->response->articles;
        }
    }

    public function userFeed(string $type)
    {
        $url = "https://newsapi.org/v2/top-headlines?";
        $url .= $type;
        $url .= "&apiKey=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->status == "ok") {
            $this->data = $this->fetch->response->articles;
        }
    }

    public function search(string $search)
    {
        $url = "https://newsapi.org/v2/everything?";
        $url .= "q=" . $search;
        $url .= "&apiKey=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->status == "ok") {
            $this->data = $this->fetch->response->articles;
        }
    }

    public function format(ApiFormatterInterface $formatter)
    {
        $formatted = [];

        foreach ($this->data as $index => $object) {
            // $formatter = $apiFormatter;
            $formatter->setTitle($object->title);
            $formatter->setDescription($object->description);
            $formatter->setContent($object->content);
            $formatter->setImage($object->urlToImage);
            $formatter->setUrl($object->url);
            $formatter->setPublishedAt($object->publishedAt);
            $formatter->setSourceId($object->source->id);
            $formatter->setSourceName($object->source->name);
            $formatter->setAuthorId($object->author);
            $formatter->setAuthorName($object->author);
            $formatter->setCategoryId($object->category);
            $formatter->setCategoryName($object->category);
            $formatted[$index] = $formatter->getAllPropertiesAsObject();
            $formatter->reset();
        }

        // foreach ($this->data as $index => $object) {
        //     foreach ($object as $key => $value) {
        //         if (!in_array($key, $this->api_data_key)) {
        //             continue;
        //         }
        //         if (!is_object($value)) {
        //             $formatted[$index][$format_fields[array_search($key, $this->api_data_key)]] = $value;
        //         } else {
        //             foreach ($value as $key3 => $value3) {
        //                 $formatted[$index][$format_fields[array_search($key, $this->api_data_key)] . "_" . $key3] = $value3;
        //             }
        //         }
        //     }
        // }

        $this->formatted = $formatted;
    }
}
