<?php

namespace App\GraphQL\Queries;

use App\Http\Controllers\ArticleController;
use App\Helpers\API\NewsAPI;
use App\Helpers\API\NewYorkTimeAPI;
use App\Helpers\API\GuardianApi;
use App\Helpers\Fetch;

final class GetDefaultArticle
{
    public array $article_schema = [
        'title',
        'description',
        'content',
        'image',
        'url',
        'publishedAt',
        'source',
        'author_name',
        'category_name'
    ];
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {


        $fetch = new Fetch();

        $newsApi = new NewsAPI($fetch);
        $newsApi->headlines();
        $newsApi->format($this->article_schema);

        $newYorkTimeApi = new NewYorkTimeAPI($fetch);
        $newYorkTimeApi->headlines();
        $newYorkTimeApi->format($this->article_schema);

        $guardianApi = new GuardianApi($fetch);
        $guardianApi->headlines();
        $guardianApi->format($this->article_schema);

        $articles = [];
        $articles = array_merge($articles, $newsApi->formatted);
        $articles = array_merge($articles, $newYorkTimeApi->formatted);
        $articles = array_merge($articles, $guardianApi->formatted);

        return $articles;

    }
}
