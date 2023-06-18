<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
// use App\Helpers\Interfaces;

class Article extends Model
{
    use HasFactory;
    use HasUuids;

    protected $filable = [
        'title',
        'description',
        'content',
        'image',
        'publishedAt',
        'url',
        'category_id',
        'category_name',
        'source_id',
        'source_name',
        'author_id',
        'author_name',
        'read_later',
        'favorites',
        'already_read',
    ];

    /**
     * Get the user that owns the Article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function upsert(array $fields = [], string $user_id)
    // {
    //     $article = $this->where('url', $fields['url'])->where("user_id", $user_id)->first();
    //     if (!$article) {
    //         $article = $this;
    //         $article->user_id = $user_id;
    //     }

    //     foreach ($fields as $key => $value) {
    //         $article->$key = $value;
    //     }

    //     $article->save();
    //     $this->article = $article;
    // }
}
