<?php

namespace App\Helpers\Interfaces;

interface ApiInterface
{
    public function __construct(FetchInterface $fetch);

    public function headlines();

    public function userFeed(string $params);

    public function search(string $params);

    public function format(array $format_fields);
}
