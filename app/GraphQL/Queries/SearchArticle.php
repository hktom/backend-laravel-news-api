<?php

namespace App\GraphQL\Queries;

use App\Helpers\API\NewsAPI;
use App\Helpers\API\NewYorkTimeAPI;
use App\Helpers\API\GuardianApi;
use App\Helpers\Fetch;
// use App\Helpers\Interfaces\ApiFormatterInterface;
use App\Helpers\API\ApiFormatter;
// use App\Helpers\API\ApiQuery;

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

        $fetch = new Fetch();
        $formatter = new ApiFormatter();
        $newsApi = new NewsAPI($fetch);
        $newYorkTimeApi = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);
        
        // $apiQuery = new ApiQuery();
        // $apiQuery->setSearch($args['search']);
        // $apiQuery->getQuery();

        $newsApi->search(urlencode($args['search']));
        $newYorkTimeApi->search(urlencode($args['search']));
        $guardianApi->search(urlencode($args['search']));

        $newsApi->format($formatter);
        $newYorkTimeApi->format($formatter);
        $guardianApi->format($formatter);

        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);

        return $articles;
    }
}
