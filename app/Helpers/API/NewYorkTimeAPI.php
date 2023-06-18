<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;
use App\Helpers\Interfaces\ApiFormatterInterface;

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

    public function format(ApiFormatterInterface $formatter)
    {
        $formatted = [];

        foreach ($this->data as $index => $object) {
            $formatter->setDescription(isset($object->abstract) ?: '');
            $formatter->setContent(isset($object->lead_paragraph) ?: '');
            $formatter->setUrl(isset($object->web_url) ?: '');
            $formatter->setPublishedAt(isset($object->pub_date) ?: '');
            $formatter->setSourceName(isset($object->source) ?: '');
            $formatter->setCategoryName(isset($object->section_name) ?: '');

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
