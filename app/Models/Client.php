<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    public $timestamps = true;
    protected $primaryKey = 'клиент_id';

    protected $fillable = [
        'имя',
        'фамилия',
        'телефон',
        'email'
    ];

    protected $attributes = [
        'email' => null
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'клиент_id');
    }
}
