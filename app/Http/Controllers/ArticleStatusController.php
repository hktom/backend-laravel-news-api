<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleStatusController extends Controller
{
    public Article $article;

    public function __construct()
    {
    }

    public function toggle(array $fields = [])
    {
        $article = Article::where('url', $fields['url'])->first();
        if (!$article) {
            $article = new Article();
        }

        foreach ($fields as $key => $value) {
            $article->$key = $value;
        }

        $article->save();
        $this->article = $article;
    }
}
