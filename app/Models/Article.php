<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Article extends Model
{
    use HasFactory;
    protected $table = 'article';
    protected $fillable = [
        
        'author_id',
        'title',
        'publication_date',
        'status',
        'external_url',
        'like_count',
        'comment',
        'image',
        'category',
    ];
    public $timestamps = false;
  
    public function getimageUrlAttribute()
    {
        if ($this->image) {
            // Assuming $baseUrl is the base URL where images are stored
            $baseUrl = url('/');
            
            // Return the complete URL for the image
            return $baseUrl.'/'. $this->image;
        }
        
        return null;
    }
}