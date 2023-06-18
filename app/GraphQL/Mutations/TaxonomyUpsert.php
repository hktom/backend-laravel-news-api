<?php

namespace App\GraphQL\Mutations;

use App\Helpers\Authentication;
use App\Helpers\TaxonomyUpdater;

final class TaxonomyUpsert
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = new Authentication();
        $taxonomy = new TaxonomyUpdater($user->user_id);
        $taxonomy->upsert($args, isset($args['parent_id']) ? $args['parent_id'] : null);
        return $taxonomy->taxonomy;
    }
}
