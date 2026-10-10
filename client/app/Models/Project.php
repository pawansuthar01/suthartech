<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'slug', 'category', 'summary', 'body', 'image_path', 'technologies', 'project_url', 'is_featured', 'sort_order', 'status', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return ['technologies' => 'array', 'is_featured' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', ['active', 'published']);
    }
}
