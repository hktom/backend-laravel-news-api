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

class NewYorkApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $fetch = new Fetch();
        $newYorkApi = new NewYorkTimeAPI($fetch);
        $formatter = new ApiFormatter();
        $newYorkApi->headlines();

        $fetch->pushUrls([
            $newYorkApi->name => $newYorkApi->url
        ]);
        $fetch->getHttp();

        $newYorkApi->format($formatter, $fetch->responses[$newYorkApi->name]);
        dump($newYorkApi->url);
        dump($newYorkApi->formatted[0]);

        $this->assertTrue($fetch->responses[$newYorkApi->name]->status == "OK");
    }
}
