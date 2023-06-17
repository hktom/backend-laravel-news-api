<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\PreferenceController;

final class Preference
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $preference = new PreferenceController();
        $preference->upsert($args['name'], $args['type'], $args['feed'], $args['folder']);
        return $preference->preference;
    }
}
