<?php

namespace App\Helpers\Interfaces;

interface FetchInterface
{
    public function __construct();

    public function getHttp();

    public function close();

    public function pushUrls(array $url);
}
