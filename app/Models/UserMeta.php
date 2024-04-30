<?php

namespace App\Models;
use App\Model\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'users_meta'; 
    use HasFactory;
}
