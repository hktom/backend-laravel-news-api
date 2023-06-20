<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;
use App\Helpers\Interfaces\ApiFormatterInterface;
// use App\Helpers\Interfaces\ApiQueryInterface;

class NewYorkTimeAPI implements ApiInterface
{

    public array $data = [];

    public array $formatted = [];

    private $api_key;

    public string $url = '';

    public string $name = 'NewYorkTimeAPI';


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('NEW_YORK_TIME_API_KEY');
    }

    public function headlines()
    {
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?api-key=" . $this->api_key;
        $this->url = $url;
    }

    public function userFeed(array $apiQuery)
    {
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?";

        if ($apiQuery['type'] == 'category' && $apiQuery['queries']) {
            $url .= "fq=news_desk:" . $apiQuery['queries'];
        }
        
        if ($apiQuery['type'] =='author' && $apiQuery['queries']) {
            $url .= "fq=persons:" . $apiQuery['queries'];
        }
        
        if ($apiQuery['type'] == 'source' && $apiQuery['queries']) {
            $url .= "fq=source:" . $apiQuery['queries'];
        }

        $url .= "&api-key=" . $this->api_key;

        $this->url = $url;
    }

    public function search(string $search)
    {
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?";
        $url .= "q=" . $search;
        $url .= "&api-key=" . $this->api_key;

        $this->url = $url;
    }

    public function format(ApiFormatterInterface $formatter, object $data)
    {
        $formatted = [];

        if ($data->status == "OK") {
            $this->data = $data->response->docs;
        }

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

            if (isset($object->headline) && is_object($object->headline)) {
                $formatter->setTitle($object->headline->main);
            }

            if (isset($object->byline) && count($object->byline->person) > 0 && is_object($object->byline)) {

                if ($object->byline->person[0]->firstname || $object->byline->person[0]->lastname)
                    $formatter->setAuthorName($object->byline->person[0]->firstname . " " . $object->byline->person[0]->lastname);
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
