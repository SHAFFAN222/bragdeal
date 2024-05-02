<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'start_date',
        'end_date',
        'type',
        'attachment',
        'status',
        
     
       
    ];
    public $timestamps = false;
  
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
}