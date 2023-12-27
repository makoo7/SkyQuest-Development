<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryPhonecode extends Model
{
    use HasFactory;

    protected $table = "country_phonecode";

    public $timestamps = false;

    protected $fillable = [
        'phonecode'
    ];

}
