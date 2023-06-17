<?php

namespace App\GraphQL\Queries;

use App\Http\Controllers\ArticleController;

final class SearchArticle
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): array
    {
        if (!$args['search']) {
            return [];
        }
        $articles = new ArticleController();
        $articles->searchArticle($args['search']);
        return $articles->articles;
    }
}
