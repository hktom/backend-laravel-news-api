<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Helpers\API\NewsAPI;
use App\Helpers\API\NewYorkTimeAPI;
use App\Helpers\API\GuardianApi;
use App\Helpers\API\ApiFormatter;
use App\Helpers\Fetch;

use App\Models\Setting;
use App\Models\Taxonomy;
use App\Helpers\API\ApiQuery;
use App\Helpers\FilterTaxonomy;


class MyFeedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user_id = "997286fc-3dc1-47e6-a712-cbdbf7076108";
        $fetch = new Fetch();
        $formatter = new ApiFormatter();
        $newsApi = new NewsAPI($fetch);
        $newYorkTimeApi = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);
        $apiQuery = new ApiQuery();

        $taxonomies = Taxonomy::where('user_id', $user_id)->get();

        if (count($taxonomies) == 0) {
            $this->assertTrue(true);
        }

        $filterTaxonomy = new FilterTaxonomy($taxonomies);
        $settings = Setting::where('user_id', $user_id)->get();


        foreach ($settings as $setting) {
            if ($setting->feed_by) {
                $filterTaxonomy->filter($setting->feed_by);
                $apiQuery->setQueries($setting->feed_by, $filterTaxonomy->data);
            }
        }


        $newsApi->userFeed($apiQuery);
        if($newsApi->url){
            $fetch->pushUrls($newsApi->url, $newsApi->name);
        }

        $newYorkTimeApi->userFeed($apiQuery);

        if($newYorkTimeApi->url){
            $fetch->pushUrls($newYorkTimeApi->url, $newYorkTimeApi->name);
        }

        $guardianApi->userFeed($apiQuery);
        if($guardianApi->url){
            $fetch->pushUrls($guardianApi->url, $guardianApi->name);
        }

        dump('url ====>');
        dump($newsApi->url);
        dump($newYorkTimeApi->url);
        dump($guardianApi->url);

        $fetch->getHttp();

        // $newsApi->format($formatter);
        // $newYorkTimeApi->format($formatter);
        // $guardianApi->format($formatter);

        if($newsApi->url){
            $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        }
        
        if($newYorkTimeApi->url){
            $newYorkTimeApi->format($formatter, $fetch->responses[$newYorkTimeApi->name]);
        }

        if($guardianApi->url){
            $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);
        }

        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);
        $fetch->close();

        $this->assertTrue(count($articles) > 0);
    }
}
