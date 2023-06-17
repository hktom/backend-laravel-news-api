<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleStatusController extends Controller
{
    public function __construct()
    {
    }

    public function toggle(string $status, string $url, array $fields = [])
    {
        $article = Article::where('url', $url)->first();
        if (!$article) {
            $article = new Article();
            foreach ($fields as $key => $value) {
                $article->$key = $value;
            }
        } else {
            $article->$status = !$article->$status;
        }

        $article->save();
        return $article;
    }
}