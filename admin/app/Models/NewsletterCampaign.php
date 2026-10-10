<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterCampaign extends Model
{
    protected $fillable = ['subject', 'body', 'sent_count', 'failed_count', 'status', 'sent_at'];
}
