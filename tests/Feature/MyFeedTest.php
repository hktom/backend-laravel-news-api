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
        $apiQuery = ['type' => '', 'queries' => ''];
        $filterTaxonomy = new FilterTaxonomy();
        $settings = Setting::where('user_id', $user_id)->first();

        if (isset($settings->feed_by)) {
            $taxonomies = Taxonomy::where('user_id', $user_id)->where('type', $settings->feed_by)->get()->toArray();
            $filterTaxonomy->filter($taxonomies);
            $apiQuery['type'] = $settings->feed_by;
            $apiQuery['queries'] = $filterTaxonomy->data;
        }

        $newsApi->userFeed($apiQuery);
        $newYorkTimeApi->userFeed($apiQuery);
        $guardianApi->userFeed($apiQuery);


        $fetch->pushUrls([
            $newsApi->name => $newsApi->url,
            $newYorkTimeApi->name => $newYorkTimeApi->url,
            $guardianApi->name => $guardianApi->url
        ]);

        $this->assertTrue($apiQuery['type'] == $settings->feed_by);
        $this->assertTrue($newsApi->url!='');
        $this->assertTrue($newYorkTimeApi->url!='');
        $this->assertTrue($guardianApi->url!='');


        dump('url ====>');
        dump($apiQuery['type']);
        dump($newsApi->url);
        dump($newYorkTimeApi->url);
        dump($guardianApi->url);

        $fetch->getHttp();
        
        $newYorkTimeApi->format($formatter, $fetch->responses[$newYorkTimeApi->name]);
        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);

        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);
        $fetch->close();

        
        $this->assertTrue(count($articles) > 0);
    }
}
