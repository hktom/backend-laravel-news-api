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

class FeedApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_feed(): void
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


        $this->assertTrue($fetch->responses[$guardianApi->name]->response->status == "ok");
        $this->assertTrue($fetch->responses[$newsApi->name]->status == "ok");
        $this->assertTrue($fetch->responses[$newYorkTimeAPI->name]->status == "OK");
    }

    public function test_search(): void
    {
        $args = [
            'search' => 'elon Musk'
        ];

        $fetch = new Fetch();
        $formatter = new ApiFormatter();
        $newsApi = new NewsAPI($fetch);
        $newYorkTimeAPI = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);

        $newsApi->search(urlencode($args['search']));
        $newYorkTimeAPI->search(urlencode($args['search']));
        $guardianApi->search(urlencode($args['search']));

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

        $articles = array_merge($newsApi->formatted, $newYorkTimeAPI->formatted, $guardianApi->formatted);

        dump(count($articles));

        $this->assertTrue($fetch->responses[$guardianApi->name]->response->status == "ok");
        $this->assertTrue($fetch->responses[$newsApi->name]->status == "ok");
        $this->assertTrue($fetch->responses[$newYorkTimeAPI->name]->status == "OK");
    }
}
