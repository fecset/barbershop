<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $fillable = ['клиент_id', 'мастер_id', 'услуга_id', 'дата_время', 'статус'];
    public $timestamps = true;
    protected $primaryKey = 'запись_id';
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'клиент_id', 'клиент_id');
    }

    
    public function master()
    {
        return $this->belongsTo(Master::class, 'мастер_id', 'мастер_id');
    }

    
    public function service()
    {
        return $this->belongsTo(Service::class, 'услуга_id', 'услуга_id');
    }
}
