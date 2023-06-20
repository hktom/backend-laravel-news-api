<?php

// silence is golden

// namespace App\GraphQL\Queries;

// use App\Helpers\API\NewsAPI;
// use App\Helpers\API\NewYorkTimeAPI;
// use App\Helpers\API\GuardianApi;
// use App\Helpers\API\ApiFormatter;
// use App\Helpers\Fetch;
// use App\Helpers\Authentication;
// use App\Helpers\Interfaces\ApiInterface;
// use App\Helpers\Interfaces\ApiFormatterInterface;
// use App\Models\Setting;
// use App\Models\Taxonomy;
// use App\Helpers\Interfaces\ApiQueryInterface;
// use App\Helpers\API\ApiQuery;

// final class Home
// {

//     public $taxonomies;

//     /**
//      * @param  null  $_
//      * @param  array{}  $args
//      */
//     public function __invoke($_, array $args)
//     {

//         $auth = new Authentication();
//         $fetch = new Fetch();
//         $formatter = new ApiFormatter();
//         $newsApi = new NewsAPI($fetch);
//         $newYorkTimeApi = new NewYorkTimeAPI($fetch);
//         $guardianApi = new GuardianApi($fetch);
//         $apiQuery = new ApiQuery();

//         if (!$auth->user_id) {
//             return $this->noAuthenticatedFeed($newsApi, $newYorkTimeApi, $guardianApi, $formatter, $apiQuery);
//         }

//         $this->taxonomies = Taxonomy::where('user_id', $auth->user_id)->get();
//         $settings = Setting::where('user_id', $auth->user_id)->get();

//         foreach ($settings as $setting) {
//             if ($setting->feed_by) {
//                 $apiQuery->setQueries($setting->feed_by, $this->filterTaxonomy($setting->feed_by));
//             }
//         }

//         $article = $this->authenticatedFeed($newsApi, $newYorkTimeApi, $guardianApi, $formatter, $apiQuery);

//         return [
//             'user' => $auth->user,
//             'feed' => $article,
//             'settings' => $settings,
//             'taxonomies' => $this->taxonomies,
//             'filterBy' => $apiQuery->type,
//             'filters' => $apiQuery['queries'],
//         ];
//     }

//     private function noAuthenticatedFeed(ApiInterface $newsApi, ApiInterface $newYorkTimeApi, ApiInterface $guardianApi, ApiFormatterInterface $apiFormatter, ApiQueryInterface $apiQuery)
//     {

//         $newsApi->headlines();
//         $newsApi->format($apiFormatter);

//         $newYorkTimeApi->headlines();
//         $newYorkTimeApi->format($apiFormatter);

//         $guardianApi->headlines();
//         $guardianApi->format($apiFormatter);

//         $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);


//         return ['feed' => $articles];
//     }

//     private function authenticatedFeed(ApiInterface $newsApi, ApiInterface $newYorkTimeApi, ApiInterface $guardianApi, ApiFormatterInterface $apiFormatter, ApiQueryInterface $apiQuery)
//     {
//         if ($apiQuery->type) {
//             $newsApi->userFeed($apiQuery);
//             $newYorkTimeApi->userFeed($apiQuery);
//             $guardianApi->userFeed($apiQuery);
//         } else {
//             $newsApi->headlines();
//             $newYorkTimeApi->headlines();
//             $guardianApi->headlines();
//         }

//         $newsApi->format($apiFormatter);
//         $newYorkTimeApi->format($apiFormatter);
//         $guardianApi->format($apiFormatter);
//         $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);

//         return $articles;
//     }

//     public function filterTaxonomy(string $type)
//     {
//         $data = [];

//         foreach ($this->taxonomies as $taxonomy) {
//             if ($taxonomy->type == $type) {
//                 $data[] = $taxonomy->slug;
//             }
//         }

//         return $data;
//     }
// }
