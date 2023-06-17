<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class GetUserFeedController extends Controller
{
    public array $articles = [];
    private string $user_id;
    private array $feed_by;
    private string $feed_by_type;

    public function __construct(array $feed_by)
    {
        $auth = new AuthController();
        if (!$auth->user_id) {
            throw new \Exception("User not found", 1);
        }
        $this->user_id = $auth->user_id;
        $setting = Setting::where('user_id', $auth->user_id)->where('feed_by', '!=', null)->first();
        if ($setting) {
            $this->feed_by_type = $setting->feed_by;
            $this->feed_by = $feed_by[$this->feed_by_type];
        } else {
            $this->feed_by_type = '';
            $this->feed_by = [];
        }
    }

    public function fetch()
    {
        $article = new ArticleController(env('NEWS_API_KEY'));
        if ($this->feed_by_type && $this->feed_by) {
    
            $reduce = array_map(function ($item) {
                return $item['name'];
            }, $this->feed_by);

            $args = $this->feed_by_type . "=" . implode(',', $reduce);
            $article->getPersonalize($args);
        } else {
            $article->getHeadline();
        }
        $this->articles = $article->articles;
    }
}
