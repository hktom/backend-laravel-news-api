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
        $newYorkTimeApi = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);

        $newsApi->headlines();
        $newYorkTimeApi->headlines();
        $guardianApi->headlines();

        $fetch->pushUrls([
            $newsApi->name => $newsApi->url,
            $newYorkTimeApi->name => $newYorkTimeApi->url,
            $guardianApi->name => $guardianApi->url
        ]);

        $fetch->getHttp();

        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        $newYorkTimeApi->format($formatter, $fetch->responses[$newYorkTimeApi->name]);
        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);

        $fetch->close();


        $this->assertTrue($fetch->responses[$guardianApi->name]->response->status == "ok");
        $this->assertTrue($fetch->responses[$newsApi->name]->status == "ok");
        $this->assertTrue($fetch->responses[$newYorkTimeApi->name]->status == "OK");
    }

    public function test_search(): void
    {
        $args = [
            'search' => 'elon Musk'
        ];

        $fetch = new Fetch();
        $formatter = new ApiFormatter();
        $newsApi = new NewsAPI($fetch);
        $newYorkTimeApi = new NewYorkTimeAPI($fetch);
        $guardianApi = new GuardianApi($fetch);

        // $apiQuery = new ApiQuery();
        // $apiQuery->setSearch($args['search']);
        // $apiQuery->getQuery();

        $newsApi->search(urlencode($args['search']));
        $newYorkTimeApi->search(urlencode($args['search']));
        $guardianApi->search(urlencode($args['search']));

        $fetch->pushUrls([
            $newsApi->name => $newsApi->url,
            $newYorkTimeApi->name => $newYorkTimeApi->url,
            $guardianApi->name => $guardianApi->url
        ]);


        $fetch->getHttp();

        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        $newYorkTimeApi->format($formatter, $fetch->responses[$newYorkTimeApi->name]);
        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);

        $fetch->close();

        // $newsApi->format($formatter);
        // $newYorkTimeApi->format($formatter);
        // $guardianApi->format($formatter);
        $articles = array_merge($newsApi->formatted, $newYorkTimeApi->formatted, $guardianApi->formatted);

        dump("............... searc founds");
        dump(count($articles));

        $this->assertTrue($fetch->responses[$guardianApi->name]->response->status == "ok");
        $this->assertTrue($fetch->responses[$newsApi->name]->status == "ok");
        $this->assertTrue($fetch->responses[$newYorkTimeApi->name]->status == "OK");
    }
}
