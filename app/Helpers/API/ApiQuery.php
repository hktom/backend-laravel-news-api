<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiQueryInterface;

class ApiQuery implements ApiQueryInterface
{

    private array $categories;
    private array $sources;
    private array $authors;
    public array $queries;

    public function __construct()
    {
        $this->categories = [];
        $this->sources = [];
        $this->authors = [];
        $this->queries = [];
    }

    // setter
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
    }

    public function setSources(array $sources)
    {
        $this->sources = $sources;
    }

    public function setAuthors(array $authors)
    {
        $this->authors = $authors;
    }

    // public function setSearch(string $search)
    // {
    //     $this->search = $search;
    // }

    public function getQuery()
    {
        $this->queries = [
            'category' => implode(',', $this->categories),
            'sources' => implode(',', $this->sources),
            'authors' => implode(',', $this->authors),
            // 'search' => $this->search,
        ];
    }

}