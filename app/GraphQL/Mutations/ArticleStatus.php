<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\ArticleStatusController;

final class ArticleStatus
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $article = new ArticleStatusController();
        $article->toggle($args);
        return $article->article;
    }
}
