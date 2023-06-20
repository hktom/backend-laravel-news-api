<?php

namespace App\Helpers\API;


use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;
use App\Helpers\Interfaces\ApiFormatterInterface;
use App\Helpers\Interfaces\ApiQueryInterface;

class GuardianApi implements ApiInterface
{
    public array $data = [];

    public array $formatted = [];

    private $api_key;

    public string $url = '';

    public string $name = 'guardianApi';


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('GUARDIAN_API_KEY');
    }

    public function headlines()
    {
        $url = "https://content.guardianapis.com/search?lang=en&show-fields=thumbnail, byline&show-tags=keyword&api-key=" . $this->api_key;

        $this->url = $url;
    }

    public function userFeed(array $apiQuery)
    {
        $url = "https://content.guardianapis.com/search?lang=en&show-fields=thumbnail, byline&show-tags=keyword&";

        if ($apiQuery['type'] == 'author' && $apiQuery['queries']) {
            $url .= "query-fields=byline&";
        }

        $q = explode(',', $apiQuery['queries']);
        $url .= "q=" . implode(" OR ", $q);

        $url .= "&api-key=" . $this->api_key;


        $this->url = $url;
    }

    public function search(string $search)
    {
        $url = "https://content.guardianapis.com/search?lang=en&show-fields=thumbnail,byline&show-tags=keyword";
        $url .= '&q="' . $search . '"';
        $url .= "&api-key=" . $this->api_key;

        $this->url = $url;
    }

    public function format(ApiFormatterInterface $apiFormatter, object $data)
    {
        $formatted = [];

        if ($data->response && $data->response->status == "ok") {
            $this->data = $data->response->results;
        }

        foreach ($this->data as $index => $object) {
            
            $apiFormatter->setSourceName("The Guardian");

            if (isset($object->webTitle)) {
                $apiFormatter->setTitle($object->webTitle);
            }

            if (isset($object->webUrl)) {
                $apiFormatter->setUrl($object->webUrl);
            }

            if (isset($object->webPublicationDate)) {
                $apiFormatter->setPublishedAt($object->webPublicationDate);
            }

            if (isset($object->tags) && count($object->tags) > 0) {
                $apiFormatter->setCategoryId($object->tags[0]->sectionId);
                $apiFormatter->setCategoryName($object->tags[0]->sectionName);
            }

            if (isset($object->fields) && is_object($object->fields)) {
                $apiFormatter->setImage($object->fields->thumbnail);
                $apiFormatter->setAuthorName($object->fields->byline);
            }



            $formatted[$index] = $apiFormatter->getAllPropertiesAsObject();
            $apiFormatter->reset();
        }

        $this->formatted = $formatted;
    }
}
