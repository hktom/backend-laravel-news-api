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
        $fetch->pushUrls($newsApi->url, $newsApi->name);

        $newYorkTimeApi->headlines();
        $fetch->pushUrls($newYorkTimeApi->url, $newYorkTimeApi->name);

        $guardianApi->headlines();
        $fetch->pushUrls($guardianApi->url, $guardianApi->name);

        $fetch->getHttp();

        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        $newYorkTimeApi->format($formatter, $fetch->responses[$newYorkTimeApi->name]);
        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);

        $fetch->close();


        $this->assertTrue($fetch->responses[$guardianApi->name]->response->status == "ok");
        $this->assertTrue($fetch->responses[$newsApi->name]->status == "ok");
        $this->assertTrue($fetch->responses[$newYorkTimeApi->name]->status == "OK");
    }
}
