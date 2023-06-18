<?php

namespace App\Helpers\Interfaces;

interface FetchInterface
{
    public function __construct();

    public function get(string $url);
}