<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingUpdater
{
    private string $user_id;
    public Setting $setting;

    public function __construct(string $user_id)
    {
        $this->user_id = $user_id;
    }

    public function upsert(array $fields)
    {
        $setting = Setting::where('user_id', $this->user_id)->first();
        if (!$setting) {
            $setting = new Setting();
            $setting->user_id = $this->user_id;
        }

        foreach ($fields as $key => $value) {
            $setting->$key = $value;
        }

        $setting->save();
        $this->setting = $setting;
    }
}
