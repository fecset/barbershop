<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'administrators';
    protected $primaryKey = 'администратор_id';
    public $incrementing = true;

    protected $fillable = ['имя', 'логин', 'пароль'];
}
