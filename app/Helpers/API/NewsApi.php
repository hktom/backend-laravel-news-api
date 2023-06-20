<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;
use App\Helpers\Interfaces\ApiFormatterInterface;
use App\Helpers\Interfaces\ApiQueryInterface;

class NewsApi implements ApiInterface
{

    private $api_key;

    public array $data = [];

    public array $formatted = [];

    public string $url = '';

    public string $name = 'newsapi';


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('NEWS_API_KEY');
    }

    public function headlines()
    {
        $this->url = "https://newsapi.org/v2/top-headlines?language=en&apiKey=" . $this->api_key;
    }

    public function userFeed($apiQuery)
    {
        $url = "https://newsapi.org/v2/everything?language=en&";

        if ($apiQuery['type'] == 'source' && $apiQuery['queries']) {
            $sources = explode(',', $apiQuery['queries']);
            $q = array_slice($sources, 0, 18);
            $url .= "sources=" . implode(" , ", $q);
        } else {

            $q = explode(',', $apiQuery['queries']);
            $url .= "q=" . implode(" OR ", $q);
        }

        $url .= "&apiKey=" . $this->api_key;

        $this->url = $url;
    }

    public function search(string $search)
    {
        $url = "https://newsapi.org/v2/everything?language=en&";
        $url .= "q=" . $search;
        $url .= "&apiKey=" . $this->api_key;

        $this->url = $url;
    }

    public function format(ApiFormatterInterface $formatter, object $data)
    {

        $formatted = [];

        if ($data->status != "ok") {
            return;
        }
        $this->data = $data->articles;


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
                $formatter->setSourceName($object->source->id);
            }

            if (isset($object->source->name)) {
                $formatter->setSourceName($object->source->name);
            }

            if (isset($object->author)) {
                $formatter->setAuthorName($object->author);
            }

            if (isset($object->category)) {
                // $formatter->setCategoryId($object->category);
                $formatter->setCategoryName($object->category);
            }

            $formatted[$index] = $formatter->getAllPropertiesAsObject();
            $formatter->reset();
        }

        $this->formatted = $formatted;
    }
}
