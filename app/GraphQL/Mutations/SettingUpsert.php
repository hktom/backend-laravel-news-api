<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\SettingController;

final class SettingUpsert
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $setting = new SettingController();
        $setting->upsert($args);
        return $setting->setting;
    }
}
