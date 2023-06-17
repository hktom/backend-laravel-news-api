<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\TaxonomyController;

final class TaxonomyUpsert
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $taxonomy = new TaxonomyController();
        $taxonomy->upsert($args['name'], $args['type'], $args['parent_id']);
        return $taxonomy->taxonomy;
    }
}
