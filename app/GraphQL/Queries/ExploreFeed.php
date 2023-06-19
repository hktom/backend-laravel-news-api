<?php

namespace App\GraphQL\Queries;

use App\Helpers\API\NewsAPI;
use App\Helpers\API\NewYorkTimeAPI;
use App\Helpers\API\GuardianApi;
use App\Helpers\API\ApiFormatter;
use App\Helpers\Fetch;
// use App\Helpers\Authentication;
// use App\Helpers\Interfaces\ApiInterface;
// use App\Helpers\Interfaces\ApiFormatterInterface;
// use App\Models\Setting;
// use App\Models\Taxonomy;
// use App\Helpers\Interfaces\ApiQueryInterface;
// use App\Helpers\API\ApiQuery;
// use App\Helpers\FilterTaxonomy;

final class ExploreFeed
{

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $fetch = new Fetch();
        $formatter = new ApiFormatter();
        $newsApi = new NewsAPI($fetch);
        $newYorkTimeApi = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);

        $newsApi->headlines();
        
        $fetch->pushUrls($newsApi->url, $newsApi->name);
        $fetch->getHttp();

        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);

        // $newYorkTimeApi->headlines();
        // $newYorkTimeApi->format($formatter);

        // $guardianApi->headlines();
        // $guardianApi->format($formatter);

        $fetch->close();

        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);

        return $articles;
    }
}
