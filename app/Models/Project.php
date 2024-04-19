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
    
}