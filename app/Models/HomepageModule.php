<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageModule extends Model
{
    use HasFactory;

    protected $table = "homepage_modules";

    protected $fillable = [
        'item_id', 'item_type'
    ];

}