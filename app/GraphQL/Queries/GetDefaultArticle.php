<?php

namespace App\GraphQL\Queries;

use App\Http\Controllers\ArticleController;
final class GetDefaultArticle
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $article = new ArticleController();
        $article = $article->getHeadline();
        return $article;
    }
}
