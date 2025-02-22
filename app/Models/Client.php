<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = ['имя', 'фамилия', 'телефон', 'email'];
    public $timestamps = true;

    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'клиент_id', 'клиент_id');
    }
}
