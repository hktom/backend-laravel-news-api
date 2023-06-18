<?php

namespace App\Helpers;

use App\Models\Setting;

class UseFeedPreferences
{
    public string $feed_by_type = ''; // author, category, source
    public array $feed_by = []; // sport, business, tech, bbc-news, cnn, etc
    private string $user_id;



    public function __construct(string $user_id)
    {
        $this->user_id = $user_id;
    }

    public function get(array $taxonomies)
    {
        $setting = Setting::where('user_id', $this->user_id)->where('feed_by', '!=', null)->first();
        if ($setting) {
            $this->feed_by_type = $setting->feed_by;
            $this->feed_by = $taxonomies[$setting->feed_by];
        }
    }
}