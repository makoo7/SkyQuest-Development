<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{
    use HasFactory;

    protected $table = "homepage_settings";

    protected $fillable = [
        'is_case_study', 'is_feedback', 'is_help', 'is_insights', 'is_process', 'is_products', 'is_awards'
    ];

}