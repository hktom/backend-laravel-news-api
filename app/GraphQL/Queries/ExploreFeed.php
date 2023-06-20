<?php

namespace App\GraphQL\Queries;

use App\Helpers\API\NewsAPI;
use App\Helpers\API\NewYorkTimeAPI;
use App\Helpers\API\GuardianApi;
use App\Helpers\API\ApiFormatter;
use App\Helpers\Fetch;

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
        $newYorkTimeAPI = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);

        $newsApi->headlines();
        $newYorkTimeAPI->headlines();
        $guardianApi->headlines();

        $fetch->pushUrls([
            $newsApi->name => $newsApi->url,
            $newYorkTimeAPI->name => $newYorkTimeAPI->url,
            $guardianApi->name => $guardianApi->url
        ]);


        $fetch->getHttp();

        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        $newYorkTimeAPI->format($formatter, $fetch->responses[$newYorkTimeAPI->name]);
        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);

        $fetch->close();

        $articles = array_merge(
            $newsApi->formatted, 
            $newYorkTimeAPI->formatted, 
            $guardianApi->formatted
        );

        return $articles;
    }
}
