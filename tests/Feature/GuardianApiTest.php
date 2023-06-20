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

class GuardianApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_feed(): void
    {
        $fetch = new Fetch();
        $guardianApi = new GuardianApi($fetch);
        $formatter = new ApiFormatter();
        $guardianApi->headlines();

        $fetch->pushUrls([
                $guardianApi->name => $guardianApi->url
            ]);
        $fetch->getHttp();

        dump($guardianApi->url);

        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);
        dump($guardianApi->url);
        dump($guardianApi->formatted[0]);

        $this->assertTrue($fetch->responses[$guardianApi->name]->response->status == "ok");
    }

    public function test_search(): void
    {
        $fetch = new Fetch();
        $guardianApi = new GuardianApi($fetch);
        $formatter = new ApiFormatter();
        $guardianApi->search('elon musk');

        $fetch->pushUrls([
                $guardianApi->name => $guardianApi->url
            ]);
        $fetch->getHttp();

        dump($guardianApi->url);

        $guardianApi->format($formatter, $fetch->responses[$guardianApi->name]);
        dump($guardianApi->url);
        dump($guardianApi->formatted[0]);

        $this->assertTrue($fetch->responses[$guardianApi->name]->response->status == "ok");
    }
}
