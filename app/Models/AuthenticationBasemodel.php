<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationBasemodel extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = "authentication_basemodel";

    protected $fillable = [
        'uuid', 'created_at', 'updated_at'
    ];

}