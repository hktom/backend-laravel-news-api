<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{

    private $api_key;

    public array $articles;
    public object $article;

    // public string $sources;
    // public string $categories;
    // public string $authors;

    public array $field_from = ['title', 'description', 'content', 'urlToImage', 'url', 'publishedAt', 'source'];

    public array $fields = ['title', 'description', 'content', 'image', 'url', 'publishedAt', 'source'];

    public function __construct(string $api_key = '')
    {
        $this->api_key = $api_key;
    }

    public function getHeadline($country = 'us')
    {
        $url = "https://newsapi.org/v2/top-headlines?country=" . $country . "&apiKey=" . $this->api_key;
        $api = new APIController($url);
        $articles = new FormatAPIController($api->data, $this->field_from, $this->fields);
        $this->articles = $articles->formatted;
    }

    public function getPersonalize(string $type)
    {
        $url = "https://newsapi.org/v2/top-headlines?";
        $url .= $type;
        $url .= "&apiKey=" . $this->api_key;

        // $data = new APIController($url);
        $api = new APIController($url);
        $articles = new FormatAPIController($api->data, $this->field_from, $this->fields);
        $this->articles = $articles->formatted;
    }

    public function searchArticle(string $search)
    {
        $url = "https://newsapi.org/v2/everything?";
        $url .= "q=" . $search;
        $url .= "&apiKey=" . $this->api_key;

        $api = new APIController($url);
        $articles = new FormatAPIController($api->data, $this->field_from, $this->fields);
        $this->articles = $articles->formatted;
        // return $articles;
        // $url .= "&from=2023-06-17&sortBy=popularity";
    }
}
