<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSampleRequest extends Model
{
    use HasFactory;

    protected $table = "report_sample_request";

    protected $fillable = [
        'report_id', 'name', 'email', 'phonecode', 'phone', 'linkedin_link', 'company_name', 'designation', 'country_id', 'message', 'ip_address', 'lastname'
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
