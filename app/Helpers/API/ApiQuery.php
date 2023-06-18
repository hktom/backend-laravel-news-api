<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiQueryInterface;

class ApiQuery implements ApiQueryInterface
{

    public array $queries = [];
    public string $type = '';

    public function __construct(){}

    public function setQueries(string $type, array $queries)
    {
        $this->type = $type;
        $this->queries[$type] = implode(',', $queries);
    }
}
