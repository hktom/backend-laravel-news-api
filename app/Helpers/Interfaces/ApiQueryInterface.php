<?php

namespace App\Helpers\Interfaces;

interface ApiQueryInterface
{
    public function __construct(string $url, array $params);

    public function setQueries(string $type, array $queries);
}
