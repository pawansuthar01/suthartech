<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $fillable = ['title', 'slug', 'hero_eyebrow', 'hero_title', 'hero_description', 'body', 'status', 'sort_order', 'meta_title', 'meta_description'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', ['active', 'published']);
    }
}
