<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'company', 'project_type', 'budget', 'message', 'attachment_path', 'read_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }
}
