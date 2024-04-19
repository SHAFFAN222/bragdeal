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

    // Additional methods or attributes can be defined here
}


?>