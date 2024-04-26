<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class portal_settings extends Model
{
    use HasFactory;
    protected $table = 'portal_settings';
    protected $fillable = [
        'user_id',
        'title',
        'email',
        'phone',
        'address',
        'color_scheme',
        'logo',
    ];


    public function getlogoUrlAttribute()
    {
        if ($this->logo) {
            // Assuming $baseUrl is the base URL where logos are stored
            $baseUrl = url('/');
            
            // Return the complete URL for the logo
            return $baseUrl.'/'. $this->logo;
        }
        
        return null;
    }
}