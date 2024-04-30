<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Define any attributes, relationships, and methods as necessary
    // For example, if you need to specify fillable fields:
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'attachment',
        'subject',
        'department',
        'priority',
    ];

    // Define relationships (if necessary)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment) {
            // Assuming $baseUrl is the base URL where attachments are stored
            $baseUrl = url('/');
            
            // Return the complete URL for the attachment
            return $baseUrl.'/'. $this->attachment;
        }
        
        return null;
    }

    // Additional methods or attributes can be defined here
}


?>