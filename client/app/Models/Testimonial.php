<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['client_name', 'client_role', 'company', 'quote', 'sort_order', 'status'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', ['active', 'published']);
    }
}
