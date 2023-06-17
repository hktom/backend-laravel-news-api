<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    private string $user_id;

    public function __construct()
    {
        $auth = new AuthController();
        if (!$auth->me()->id) {
            throw new \Exception("User not found", 1);
        }
        $this->user_id = $auth->me()->id;
    }

    public function upsertSetting(array $fields)
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
    }
}
