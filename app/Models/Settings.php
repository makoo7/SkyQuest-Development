<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $table = "settings";

    protected $fillable = [
        'satisfied_customers', 'customer_retention_rate', 'years_in_business', 'country_network', 'team_members', 'years_of_team_experience', 'forecast_year'
    ];

}