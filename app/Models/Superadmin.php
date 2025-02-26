<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Superadmin extends Authenticatable
{
    protected $table = 'super_administrators';
    protected $primaryKey = 'суперадминистратор_id';
    protected $fillable = ['имя', 'логин', 'пароль'];
    public $timestamps = true;
}
