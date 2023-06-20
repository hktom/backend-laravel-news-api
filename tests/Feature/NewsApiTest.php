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

class NewsApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        $fetch = new Fetch();
        $newsApi = new NewsAPI($fetch);
        $formatter = new ApiFormatter();
        $newsApi->headlines();

        $fetch->pushUrls([$newsApi->name => $newsApi->url]);
        
        $fetch->getHttp();

        $newsApi->format($formatter, $fetch->responses[$newsApi->name]);
        dump($newsApi->formatted[0]);

        $this->assertTrue($fetch->responses[$newsApi->name]->status == "ok");
    }
}
