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

    public string $url;

    public string $name = 'newsapi';


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('NEWS_API_KEY');
        $this->fetch = $fetch;
    }

    public function headlines()
    {
        $this->url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=" . $this->api_key;
        // $this->fetch->get($url);
        // if ($this->fetch->response->status == "ok") {
        //     $this->data = $this->fetch->response->articles;
        // }
    }

    public function userFeed(ApiQueryInterface $apiQuery)
    {
        $url = "https://newsapi.org/v2/everything?";

        if ($apiQuery->type == 'source' && $apiQuery->queries) {
            $url .= "sources=" . $apiQuery->queries['source'];
        } else if ($apiQuery->type == 'author' && $apiQuery->queries) {
            $url .= "q=" . urlencode($apiQuery->queries);
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

    public function format(ApiFormatterInterface $formatter, object $data)
    {

        $formatted = [];

        if ($data->response->status == "ok") {
            return;
        }
        $this->data = $data->response->articles;


        foreach ($this->data as $index => $object) {
            if (isset($object->title)) {
                $formatter->setTitle($object->title);
            }
            if (isset($object->description)) {
                $formatter->setDescription($object->description);
            }

            if (isset($object->content)) {
                $formatter->setContent($object->content);
            }

            if (isset($object->urlToImage)) {
                $formatter->setImage($object->urlToImage);
            }

            if (isset($object->url)) {
                $formatter->setUrl($object->url);
            }

            if (isset($object->publishedAt)) {
                $formatter->setPublishedAt($object->publishedAt);
            }

            if (isset($object->source->id)) {
                $formatter->setSourceId($object->source->id);
            }

            if (isset($object->source->name)) {
                $formatter->setSourceName($object->source->name);
            }

            if (isset($object->author)) {
                $formatter->setAuthorId($object->author);
                $formatter->setAuthorName($object->author);
            }

            if (isset($object->category)) {
                $formatter->setCategoryId($object->category);
                $formatter->setCategoryName($object->category);
            }

            $formatted[$index] = $formatter->getAllPropertiesAsObject();
            $formatter->reset();
        }

        $this->formatted = $formatted;
    }
}
