<?php

namespace App\GraphQL\Queries;

use App\Helpers\Authentication;
use App\Models\Article;

final class GetArticleBy
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $auth = new Authentication();
        $articles = Article::where('user_id', $auth->user_id)->where($args['key'], $args['value'])->get();
        return $articles;
    }
}
