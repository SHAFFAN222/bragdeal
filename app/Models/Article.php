<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $table = 'article';
    protected $fillable = [
        
        'author_id',
        'title',
        'conent',
        'publication_date',
        'status',
        'external_url',
        'like_count',
        'comment',
        'image',
        
        
     
       
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