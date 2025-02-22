<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['название', 'цена', 'специализация'];
    protected $primaryKey = 'услуга_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    // Услуга может использоваться в нескольких записях
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'услуга_id', 'услуга_id');
    }
}
