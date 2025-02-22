<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'administrators';
    protected $fillable = ['имя', 'логин', 'пароль'];
    public $timestamps = true;
}
