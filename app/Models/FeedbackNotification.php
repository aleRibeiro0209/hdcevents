<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackNotification extends Model
{
    use HasFactory;

    protected $table = 'feedback_notifications';

    protected $fillable = ['event_id', 'user_id'];
}
