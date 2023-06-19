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
        $fetch->pushUrls($newsApi->url, $newsApi->name);

        $newYorkTimeApi->search(urlencode($args['search']));
        $fetch->pushUrls($newYorkTimeApi->url, $newYorkTimeApi->name);

        $guardianApi->search(urlencode($args['search']));
        $fetch->pushUrls($guardianApi->url, $guardianApi->name);

        $fetch->getHttp();

        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        $newYorkTimeApi->format($formatter, $fetch->responses[$newYorkTimeApi->name]);
        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);

        $fetch->close();

        // $newsApi->format($formatter);
        // $newYorkTimeApi->format($formatter);
        // $guardianApi->format($formatter);

        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);

        return $articles;
    }
}
