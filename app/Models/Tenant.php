<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tenant extends Model
{
    protected $fillable = ['name', 'slug', 'domain', 'template_id', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
