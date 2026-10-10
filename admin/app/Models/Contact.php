<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['source_id', 'name', 'email', 'phone', 'subject', 'message', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];
}
