<?php

namespace App\Helpers\Interfaces;

interface ApiInterface
{
    public function __construct(FetchInterface $fetch);

    public function headlines();

    public function userFeed(array $apiQuery);

    public function search(string $params);

    public function format(ApiFormatterInterface $formatter, object $data);
}
