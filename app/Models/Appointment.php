<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = "appointments";

    protected $fillable = [
        'name', 'company_name', 'email', 'phone', 'appointment_time'
    ];

}
