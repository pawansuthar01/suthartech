<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'image_path', 'image_alt', 'topic', 'status', 'published_at', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function getTopicLabelAttribute(): string
    {
        return match ($this->topic) {
            'ai-data' => 'AI & Data',
            'api' => 'API',
            default => Str::headline($this->topic ?: 'general'),
        };
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return str_starts_with($this->image_path, 'images/')
            ? asset($this->image_path)
            : Storage::disk('public')->url($this->image_path);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', ['active', 'published'])->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
