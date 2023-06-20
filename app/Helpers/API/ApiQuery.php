<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiQueryInterface;

class ApiQuery implements ApiQueryInterface
{

    public string $queries = '';
    public string $types = '';

    public function __construct()
    {
    }

    public function setQueries(string $types, array $queries)
    {
        $this->types = $types;
        $this->queries = implode(', ', $queries);
    }
}
