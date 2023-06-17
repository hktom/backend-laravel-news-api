<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Article extends Model
{
    use HasFactory;
    use HasUuids;

    protected $filable=[
        'title',
        'description',
        'content',
        'image',
        'publishedAt',
        'url',
        'category',
        'source',
        'source_id',
        'source_name',
        'author',
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
}
