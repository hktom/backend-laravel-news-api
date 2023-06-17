<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    private string $user_id;
    public Setting $setting;

    public function __construct()
    {
        $auth = new AuthController();
        if (!$auth->user_id) {
            throw new \Exception("User not found", 1);
        }
        $this->user_id = $auth->user_id;
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
