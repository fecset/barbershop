<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Superadmin extends Model
{
    protected $table = 'super_administrators';
    protected $fillable = ['имя', 'логин', 'пароль'];
    public $timestamps = true;
}
