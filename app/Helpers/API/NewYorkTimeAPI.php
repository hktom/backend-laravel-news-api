<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;
use App\Helpers\Interfaces\ApiFormatterInterface;
use App\Helpers\Interfaces\ApiQueryInterface;

class NewYorkTimeApi implements ApiInterface
{

    public array $data = [];

    public array $formatted = [];

    private $api_key;

    private FetchInterface $fetch;


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('NEW_YORK_TIME_API_KEY');
        $this->fetch = $fetch;
    }

    public function headlines()
    {
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?api-key=" . $this->api_key;
        $this->fetch->get($url);
        if ($this->fetch->response->status == "OK") {
            $this->data = $this->fetch->response->response->docs;
        }
    }

    public function userFeed(ApiQueryInterface $apiQuery)
    {
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?";

        if ($apiQuery->queries == 'source' && $apiQuery->queries) {
            $url .= "fq=source:(" . urlencode(explode(',', $apiQuery->queries)[0]) . ")";
        } else if ($apiQuery->queries == 'category' && $apiQuery->queries) {
            $url .= "fq=news_desk:(" . urlencode($apiQuery->queries) . ")";
        } else {
            return;
        }

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

    public function format(ApiFormatterInterface $formatter)
    {
        $formatted = [];

        foreach ($this->data as $index => $object) {

            if (isset($object->lead_paragraph)) {
                $formatter->setContent($object->lead_paragraph);
            }

            if (isset($object->abstract)) {
                $formatter->setDescription($object->abstract);
            }

            if (isset($object->web_url)) {
                $formatter->setUrl($object->web_url);
            }

            if (isset($object->pub_date)) {
                $formatter->setPublishedAt($object->pub_date);
            }

            if (isset($object->source)) {
                $formatter->setSourceName($object->source);
            }

            if (isset($object->section_name)) {
                $formatter->setCategoryName($object->section_name);
            }

            if ($object->headline && is_object($object->headline)) {
                $formatter->setTitle($object->headline->main);
            }

            if (count($object->multimedia) > 0) {
                $formatter->setImage("https://www.nytimes.com/" . $object->multimedia[0]->url);
            }

            $formatted[$index] = $formatter->getAllPropertiesAsObject();
            $formatter->reset();
        }

        $this->formatted = $formatted;
    }
}
