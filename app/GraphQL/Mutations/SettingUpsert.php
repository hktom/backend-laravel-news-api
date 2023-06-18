<?php

namespace App\GraphQL\Mutations;

use App\Helpers\Authentication;
use App\Helpers\SettingUpdater;

final class SettingUpsert
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = new Authentication();
        $setting = new SettingUpdater($user->user_id);
        
        $setting->upsert($args);
        return $setting->setting;
    }
}
