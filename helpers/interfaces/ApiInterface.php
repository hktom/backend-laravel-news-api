<?php

namespace App\Helpers\Interfaces;

interface ApiInterface
{
    public function __construct(FetchInterface $fetch, ReducerInterface $reducer);

    public function headlines();

    public function userFeed(string $params);

    public function search(string $params);
}
