<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['question', 'answer', 'page', 'sort_order', 'status'];

    public function scopePublished(Builder $query, string $page = 'home'): Builder
    {
        return $query->where('page', $page)->where('status', 'active')->orderBy('sort_order')->orderBy('id');
    }
}
