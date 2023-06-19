<?php

namespace App\Helpers;

class FilterTaxonomy
{
    private array $taxonomies;
    public array $data;


    public function __construct(array $taxonomies)
    {
        $this->taxonomies = $taxonomies;
    }

    public function filter(string $type)
    {
        $data = [];

        foreach ($this->taxonomies as $taxonomy) {
            if ($taxonomy->type == $type) {
                $data[] = $taxonomy->slug;
            }
        }

        $this->data = $data;
    }
}
