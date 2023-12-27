<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageNotFoundInquiry extends Model
{
    use HasFactory;

    protected $table = "404_inquiry";

    protected $fillable = [
        'name', 'email', 'country_id', 'phone', 'company_name', 'designation', 'description'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
