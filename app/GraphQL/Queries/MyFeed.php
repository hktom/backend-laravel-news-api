<?php

namespace App\GraphQL\Queries;

use App\Helpers\API\NewsAPI;
use App\Helpers\API\NewYorkTimeAPI;
use App\Helpers\API\GuardianApi;
use App\Helpers\API\ApiFormatter;
use App\Helpers\Fetch;
use App\Helpers\Authentication;
use App\Models\Setting;
use App\Models\Taxonomy;
use App\Helpers\API\ApiQuery;
use App\Helpers\FilterTaxonomy;
// use App\Helpers\Interfaces\ApiInterface;
// use App\Helpers\Interfaces\ApiFormatterInterface;
// use App\Helpers\Interfaces\ApiQueryInterface;

final class MyFeed
{

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $auth = new Authentication();
        $fetch = new Fetch();
        $formatter = new ApiFormatter();
        $newsApi = new NewsAPI($fetch);
        $newYorkTimeApi = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);
        $apiQuery = new ApiQuery();

        $taxonomies = Taxonomy::where('user_id', $auth->user_id)->get();

        if (count($taxonomies) == 0) {
            return [];
        }

        $filterTaxonomy = new FilterTaxonomy($taxonomies);
        $settings = Setting::where('user_id', $auth->user_id)->get();


        foreach ($settings as $setting) {
            if ($setting->feed_by) {
                $filterTaxonomy->filter($setting->feed_by);
                $apiQuery->setQueries($setting->feed_by, $filterTaxonomy->data);
            }
        }


        $newsApi->userFeed($apiQuery);
        $fetch->pushUrls($newsApi->url, $newsApi->name);

        $newYorkTimeApi->userFeed($apiQuery);
        $fetch->pushUrls($newYorkTimeApi->url, $newYorkTimeApi->name);

        $guardianApi->userFeed($apiQuery);
        $fetch->pushUrls($guardianApi->url, $guardianApi->name);

        $fetch->getHttp();

        // $newsApi->format($formatter);
        // $newYorkTimeApi->format($formatter);
        // $guardianApi->format($formatter);

        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        $newYorkTimeApi->format($formatter, $fetch->responses[$newYorkTimeApi->name]);
        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);

        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);

        $fetch->close();

        return $articles;
    }
}
