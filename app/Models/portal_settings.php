<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class portal_settings extends Model
{
    use HasFactory;
    protected $table = 'portal_settings';
    //public function getTable()
    protected $fillable = [
        // public function get implements
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
            $baseUrl = url('/');
            
            // Return the complete URL for the logo
            return $baseUrl.'/'. $this->logo;
        }
        
        return null;
    }
}