<?php

namespace App\Helpers\Interfaces;

interface FetchInterface
{
    public function __construct();

    public function get(string $url);

    public function close();

    public function pushUrls(string $url, string $key);
}
