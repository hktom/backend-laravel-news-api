<?php

namespace App\Helpers\Interfaces;

interface ApiQueryInterface
{
    public function __construct(string $url, array $params);

    public function setCateGories(array $categories);
    public function setAuthors(array $Authors);
    public function setSources(array $sources);
    // public function setSearch(string $search);

    public function getQuery();

    // public function newsApi();
    // public function guardian();
    // public function newYorkTimes();
}
