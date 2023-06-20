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

final class GetArticleByTaxonomies
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $fetch = new Fetch();
        $auth = new Authentication();
        $formatter = new ApiFormatter();
        $newsApi = new NewsAPI($fetch);
        $newYorkTimeAPI = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);
        $apiQuery = ['type' => '', 'queries' => ''];
        $filterTaxonomy = new FilterTaxonomy();

        $taxonomies = Taxonomy::where('user_id', $auth->user_id)->where('type', $args['key'])->get()->toArray();
        
        if (count($taxonomies) == 0) {
            return [];
        }
        
        $filterTaxonomy->filter($taxonomies);
        $apiQuery['type'] = $args['key'];
        $apiQuery['queries'] = $filterTaxonomy->data;

        $newsApi->userFeed($apiQuery);
        $newYorkTimeAPI->userFeed($apiQuery);
        $guardianApi->userFeed($apiQuery);


        $fetch->pushUrls([
            $newsApi->name => $newsApi->url,
            $newYorkTimeAPI->name => $newYorkTimeAPI->url,
            $guardianApi->name => $guardianApi->url
        ]);

        $fetch->getHttp();

        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        $newYorkTimeAPI->format($formatter, $fetch->responses[$newYorkTimeAPI->name]);
        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);

        $articles = array_merge(
            $newsApi->formatted,
            $newYorkTimeAPI->formatted,
            $guardianApi->formatted
        );
        $fetch->close();

        return $articles;
    }
}
