<?php

namespace App\GraphQL\Mutations;

use App\Helpers\Authentication;
use App\Helpers\ArticleUpdater;

final class ArticleStatus
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = new Authentication();
        $article = new ArticleUpdater();
        $article->upsert($args, $user->user_id);
        return $article->article;
    }
}
