<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{

    private $api_key;

    // public string $sources;
    // public string $categories;
    // public string $authors;

    public array $fields = [
        'title',
        'description',
        'content',
        'image',
        'publishedAt',
        'url',
        'category',
        'source',
        'source_id',
        'read_later',
        'favorites',
        'already_read',
    ];

    public function __construct(string $api_key='')
    {
        $this->api_key = $api_key;
    }

    public function getHeadline($country = 'us')
    {
        $url = "https://newsapi.org/v2/top-headlines?" . $country . "=us&apiKey=" . $this->api_key;
        $data = new APIController($url);
        $articles = new FormatAPIController($data, ['title', 'description', 'url', 'urlToImage', 'publishedAt'], $this->fields);
        return $articles;
    }

    public function getPersonalize(string $type, string $value){
        $url = "https://newsapi.org/v2/top-headlines?";
        $url .= $type."=".$value;
        $url .= "&apiKey=".$this->api_key;

        $data = new APIController($url);
        $articles = new FormatAPIController($data, ['title', 'description', 'url', 'urlToImage', 'publishedAt'], $this->fields);
        return $articles;
    }

    public function searchArticle(string $search){
        $url = "https://newsapi.org/v2/everything?";
        $url .="q=".$search;
        $url .="&apiKey=".$this->api_key;

        $data = new APIController($url);
        $articles = new FormatAPIController($data, ['title', 'description', 'url', 'urlToImage', 'publishedAt'], $this->fields);
        return $articles;
        // $url .= "&from=2023-06-17&sortBy=popularity";
    }

    public function changeStatus(string $status, string $url){
        $article = Article::where('url', $url)->first();
        $article->$status = !$article->$status;
        $article->save();
        return $article;
    }
}