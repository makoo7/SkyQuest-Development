<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSubscribeNow extends Model
{
    use HasFactory;

    protected $table = "report_subscribe_now";

    protected $fillable = [
        'report_id', 'plan', 'name', 'email', 'phonecode', 'phone', 'linkedin_link', 'company_name', 'designation', 'country_id', 'message'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
