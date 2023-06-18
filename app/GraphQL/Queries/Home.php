<?php

namespace App\GraphQL\Queries;

use App\Helpers\API\NewsAPI;
use App\Helpers\API\NewYorkTimeAPI;
use App\Helpers\API\GuardianApi;
use App\Helpers\API\ApiFormatter;
use App\Helpers\Fetch;
use App\Helpers\Authentication;
use App\Helpers\Interfaces\ApiInterface;
use App\Helpers\Interfaces\ApiFormatterInterface;
use App\Models\Setting;
use App\Models\Taxonomy;


final class Home
{

    public $taxonomies;

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

        if (!$auth->user_id) {
            return $this->noAuthenticatedFeed($newsApi, $newYorkTimeApi, $guardianApi, $formatter);
        }

        $this->taxonomies = Taxonomy::where('user_id', $auth->user_id)->get();
        $settings = Setting::where('user_id', $auth->user_id)->get();

        $taxonomies = [];
        $taxonomies['folder'] = $this->filterTaxonomy('folder');
        $taxonomies['source'] = $this->filterTaxonomy('source');
        $taxonomies['category'] = $this->filterTaxonomy('category');
        $taxonomies['author'] = $this->filterTaxonomy('author');

        $article = $this->authenticatedFeed($newsApi, $newYorkTimeApi, $guardianApi, $formatter);

        return [
            'user' => $auth->user,
            'feed' => $article,
            'settings' => $settings,
            ...$taxonomies
        ];
    }

    private function noAuthenticatedFeed(ApiInterface $newsApi, ApiInterface $newYorkTimeApi, ApiInterface $guardianApi, ApiFormatterInterface $apiFormatter)
    {

        $newsApi->headlines();
        $newsApi->format($apiFormatter);

        $newYorkTimeApi->headlines();
        $newYorkTimeApi->format($apiFormatter);

        $guardianApi->headlines();
        $guardianApi->format($apiFormatter);

        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);


        return ['feed' => $articles];
    }

    private function authenticatedFeed(ApiInterface $newsApi, ApiInterface $newYorkTimeApi, ApiInterface $guardianApi, ApiFormatterInterface $apiFormatter)
    {

        $newsApi->userFeed('');
        $newsApi->format($apiFormatter);

        $newYorkTimeApi->userFeed('');
        $newYorkTimeApi->format($apiFormatter);

        $guardianApi->headlines('');
        $guardianApi->format($apiFormatter);

        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);

        return $articles;
    }

    public function filterTaxonomy(string $type)
    {
        $data = [];

        foreach ($this->taxonomies as $taxonomy) {
            if ($taxonomy->type == $type) {
                $data[] = $taxonomy;
            }
        }

        return $data;
    }
}
