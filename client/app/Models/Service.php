<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['title', 'slug', 'icon', 'description', 'body', 'deliverables', 'status', 'sort_order', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return ['deliverables' => 'array'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', ['active', 'published']);
    }
}
