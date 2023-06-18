<?php

namespace App\Helpers;

use App\Models\Article;
use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\FetchInterface;
use App\Helpers\Interfaces\ReducerInterface;

class NewsApi implements ApiInterface
{

    private $api_key;

    private FetchInterface $fetch;

    // private ReducerInterface $reducer;

    public object $data;

    public array $field_from;

    public array $field;


    public function __construct(FetchInterface $fetch)
    {
        $this->api_key = env('NEWS_API_KEY');
        $this->field_from =  ['title', 'description', 'content', 'urlToImage', 'url', 'publishedAt', 'source', 'author', 'category'];
        $this->field = ['title', 'description', 'content', 'image', 'url', 'publishedAt', 'source', 'author_name', 'category_name'];
        $this->fetch = $fetch;
        // $this->reducer = $reducer;
    }

    public function headlines()
    {
        $url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=" . $this->api_key;
        $this->fetch->get($url);
        $this->data = $this->fetch->response;
        // $articles = new FormatAPIController($api->data, $this->field_from, $this->fields);
        // $this->articles = $articles->formatted;
    }

    public function userFeed(string $type)
    {
        $url = "https://newsapi.org/v2/top-headlines?";
        $url .= $type;
        $url .= "&apiKey=" . $this->api_key;

        $this->fetch->get($url);
        $this->data = $this->fetch->response;
        // $data = new APIController($url);
        // $articles = new FormatAPIController($api->data, $this->field_from, $this->fields);
        // $this->articles = $articles->formatted;
    }

    public function search(string $search)
    {
        $url = "https://newsapi.org/v2/everything?";
        $url .= "q=" . $search;
        $url .= "&apiKey=" . $this->api_key;

        $this->fetch->get($url);
        $this->data = $this->fetch->response;

        // $api = $this->fetch->get($url);
        // $articles = new FormatAPIController($api->data, $this->field_from, $this->fields);
        // $this->articles = $articles->formatted;
        // return $articles;
        // $url .= "&from=2023-06-17&sortBy=popularity";
    }
}
