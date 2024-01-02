<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchSampleReport extends Model
{
    protected $table = "research_sample_report";

    protected $fillable = [
        'sales_report_id', 'report_id', 'message', 'from_id'
    ];
}
