<?php

namespace App\Helpers;

use App\Models\Article;
use App\Helpers\Interfaces\ArticleInterface;

class ArticleUpdater implements ArticleInterface
{
    public Article $article;

    public function __construct()
    {
    }

    public function upsert(array $fields = [], string $user_id)
    {
        $article = Article::where('url', $fields['url'])->where("user_id", $user_id)->first();
        if (!$article) {
            $article = new Article();
            $article->user_id = $user_id;
        }

        foreach ($fields as $key => $value) {
            $article->$key = $value;
        }

        $article->save();
        $this->article = $article;
    }
}
