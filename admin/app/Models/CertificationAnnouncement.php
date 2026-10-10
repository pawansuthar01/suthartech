<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificationAnnouncement extends Model
{
    protected $fillable = ['content_id', 'recipient_count', 'status', 'sent_at'];
}
