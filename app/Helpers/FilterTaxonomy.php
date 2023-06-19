<?php

namespace App\Helpers;

class FilterTaxonomy
{
    private object $taxonomies;
    public array $data;


    public function __construct(object $taxonomies)
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
