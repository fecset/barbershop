<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    protected $table = 'masters';
    protected $fillable = ['имя', 'специализация', 'график_работы'];
    protected $primaryKey = 'мастер_id';
    public $timestamps = true;

    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'мастер_id', 'мастер_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'специализация', 'специализация');
    }


}
