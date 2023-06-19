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

    private FetchInterface $fetch;


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('GUARDIAN_API_KEY');
        $this->fetch = $fetch;
    }

    public function headlines()
    {
        $url = "https://content.guardianapis.com/search?show-fields=thumbnail&api-key=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->response->status == "ok") {
            $this->data = $this->fetch->response->response->results;
        }
    }

    public function userFeed(ApiQueryInterface $apiQuery)
    {
        $url = "https://content.guardianapis.com/search?show-fields=thumbnail&";


        if ($apiQuery->type == 'category' && $apiQuery->queries) {
            $url .= "section=" . urlencode(explode(',', $apiQuery->queries)[0]);
        } else if ($apiQuery->type == 'author' && $apiQuery->queries) {
            $url .= "reference=" . urlencode(explode(',', $apiQuery->queries)[0]);
        } else {
            return;
        }

        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->response->status == "ok") {
            $this->data = $this->fetch->response->response->results;
        }
    }

    public function search(string $search)
    {
        $url = "https://content.guardianapis.com/search?show-fields=thumbnail,productionOffice";
        $url .= "&q=" . $search;
        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->response && $this->fetch->response->response->status == "ok") {
            $this->data = $this->fetch->response->response->results;
        }
    }

    public function format(ApiFormatterInterface $apiFormatter)
    {
        $formatted = [];

        foreach ($this->data as $index => $object) {
            if (isset($object->webTitle)) {
                $apiFormatter->setTitle($object->webTitle);
            }

            if (isset($object->webUrl)) {
                $apiFormatter->setUrl($object->webUrl);
            }

            if (isset($object->webPublicationDate)) {
                $apiFormatter->setPublishedAt($object->webPublicationDate);
            }
            if (isset($object->pillarId)) {
                $apiFormatter->setSourceId($object->pillarId);
            }

            if (isset($object->pillarName)) {
                $apiFormatter->setSourceName($object->pillarName);
            }

            if (isset($object->sectionName)) {
                $apiFormatter->setCategoryName($object->sectionName);
            }
            if (isset($object->sectionId)) {
                $apiFormatter->setCategoryId($object->sectionId);
            }

            if (isset($object->fields) && is_object($object->fields)) {
                $apiFormatter->setImage($object->fields->thumbnail);
            }

            $formatted[$index] = $apiFormatter->getAllPropertiesAsObject();
            $apiFormatter->reset();
        }

        $this->formatted = $formatted;
    }
}
