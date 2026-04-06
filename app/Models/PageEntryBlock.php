<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageEntryBlock extends Model
{
    protected $fillable = ['page_entry_id', 'type', 'content', 'sort_order'];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(PageEntry::class, 'page_entry_id');
    }
}
