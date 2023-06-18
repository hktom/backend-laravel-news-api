<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;
use App\Helpers\Interfaces\ApiFormatterInterface;
use App\Helpers\Interfaces\ApiQueryInterface;

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
    }

    public function headlines()
    {
        $url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=" . $this->api_key;
        $this->fetch->get($url);
        if ($this->fetch->response->status == "ok") {
            $this->data = $this->fetch->response->articles;
        }
    }

    public function userFeed(ApiQueryInterface $apiQuery)
    {
        $url = "https://newsapi.org/v2/everything?";

        if (isset($apiQuery->queries['source']) && $apiQuery->queries['source']) {

            $url .= "sources=" . $apiQuery->queries['source'];
        } else if (isset($apiQuery->queries['author']) && $apiQuery->queries['author']) {

            $url .= "q=" . urlencode($apiQuery->queries['author']);
            $url .= "searchIn=author";
        } else {
            return;
        }

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
            $formatter->setTitle($object->title ?: '');
            $formatter->setDescription($object->description ?: '');
            $formatter->setContent($object->content ?: '');
            $formatter->setImage($object->urlToImage ?: '');
            $formatter->setUrl($object->url ?: '');
            $formatter->setPublishedAt($object->publishedAt ?: '');
            $formatter->setSourceId($object->source->id ?: '');
            $formatter->setSourceName($object->source->name ?: '');
            $formatter->setAuthorId($object->author ?: '');
            $formatter->setAuthorName($object->author ?: '');
            // $formatter->setCategoryId(isset($object->category) ?: '');
            // $formatter->setCategoryName(isset($object->category) ?: '');
            $formatted[$index] = $formatter->getAllPropertiesAsObject();
            $formatter->reset();
        }

        $this->formatted = $formatted;
    }
}
