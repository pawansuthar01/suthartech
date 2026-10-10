<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = ['title', 'slug', 'issuer', 'credential_id', 'credential_url', 'issued_at', 'expires_at', 'description', 'image_path', 'status', 'sort_order', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return ['issued_at' => 'date', 'expires_at' => 'date'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', ['active', 'published']);
    }
}
