<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Folder extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Get all of the preferences for the Folder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preferences(): HasMany
    {
        return $this->hasMany(Preference::class, 'folder_id');
    }

    /**
     * Get the user that owns the Folder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
