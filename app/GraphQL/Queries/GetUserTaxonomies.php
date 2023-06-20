<?php

namespace App\GraphQL\Queries;

use App\Helpers\Authentication;
use App\Models\Taxonomy;

final class GetUserTaxonomies
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $auth = new Authentication();
        return Taxonomy::where('user_id', $auth->user_id)->where('type', $args['key'])->get();
    }
}
