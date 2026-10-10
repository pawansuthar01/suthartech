<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['site_name', 'contact_email', 'contact_phone', 'location', 'social_links', 'default_meta_title', 'default_meta_description', 'default_og_image'];

    protected function casts(): array
    {
        return ['social_links' => 'array'];
    }
}
