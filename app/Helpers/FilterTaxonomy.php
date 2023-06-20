<?php

namespace App\Helpers;

class FilterTaxonomy
{
    public string $data;


    public function __construct()
    {
    }

    public function filter(array $taxonomies)
    {
        $data = [];

        foreach ($taxonomies as $taxonomy) {
            $data[] = $taxonomy['name'];
        }

        $this->data = implode(', ', $data);
    }
}
