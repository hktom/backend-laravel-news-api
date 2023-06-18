<?php

namespace App\GraphQL\Queries;

use App\Helpers\API\NewsAPI;
use App\Helpers\API\NewYorkTimeAPI;
use App\Helpers\API\GuardianApi;
use App\Helpers\Fetch;


final class SearchArticle
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
    public function __invoke($_, array $args): array
    {
        if (!$args['search']) {
            return [];
        }

        $fetch = new Fetch();
        $newsApi = new NewsAPI($fetch);
        $newYorkTimeApi = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);

        $newsApi->search($args['search']);
        $newYorkTimeApi->search($args['search']);
        $guardianApi->search($args['search']);

        $newsApi->format($this->article_schema);
        $newYorkTimeApi->format($this->article_schema);
        $guardianApi->format($this->article_schema);

        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);

        return $articles;
    }
}
