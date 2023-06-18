<?php

namespace App\Helpers\Interfaces;

interface ApiQueryInterface
{
    public function __construct();

    public function setQueries(string $type, array $queries);
}
