<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleStatusController extends Controller
{
    public Article $article;
    private string $user_id;

    public function __construct()
    {
        $auth = new AuthController();
        $this->user_id = $auth->user_id;
    }

    public function toggle(array $fields = [])
    {
        $article = Article::where('url', $fields['url'])->where("user_id", $this->user_id)->first();
        if (!$article) {
            $article = new Article();
            $article->user_id = $this->user_id;
        }

        foreach ($fields as $key => $value) {
            $article->$key = $value;
        }

        $article->save();
        $this->article = $article;
    }
}
