<?php

namespace App\Helpers\API;


use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;
use App\Helpers\Interfaces\ApiFormatterInterface;


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
        $url = "https://content.guardianapis.com/search?show-fields=thumbnail,productionOffice&api-key=" . $this->api_key;
        // $url .= "&show-fields=thumbnail, productionOffice&order-by=newest";

        $this->fetch->get($url);
        if ($this->fetch->response->response->status == "ok") {
            $this->data = $this->fetch->response->response->results;
        }
    }

    public function userFeed(string $type)
    {
        $url = "https://content.guardianapis.com/search?show-fields=thumbnail,productionOffice&api-key=";
        $url .= $type;
        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->response->status == "ok") {
            $this->data = $this->fetch->response->response->results;
        }
    }

    public function search(string $search)
    {
        $url = "https://content.guardianapis.com/search?show-fields=thumbnail,productionOffice";
        $url .= "q=" . $search;
        $url .= "&api-key=" . $this->api_key;

        $this->fetch->get($url);
        if ($this->fetch->response->response->status == "ok") {
            $this->data = $this->fetch->response->response->results;
        }
    }

    public function format(ApiFormatterInterface $apiFormatter)
    {
        $formatted = [];

        foreach ($this->data as $index => $object) {
            $apiFormatter->setTitle($object->webTitle);
            $apiFormatter->setUrl($object->webUrl);
            $apiFormatter->setPublishedAt($object->webPublicationDate);
            $apiFormatter->setSourceId($object->pillarId);
            $apiFormatter->setSourceName($object->pillarName);
            $apiFormatter->setCategoryName($object->sectionName);
            $apiFormatter->setCategoryId($object->sectionId);
            $formatted[$index] = $apiFormatter->getAllPropertiesAsObject();

            if ($object->fields && is_object($object->fields)) {
                $apiFormatter->setImage($object->fields->thumbnail);
            }
            $apiFormatter->reset();
        }

        $this->formatted = $formatted;
    }
}
