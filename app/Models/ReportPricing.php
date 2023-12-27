<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPricing extends Model
{
    use HasFactory;

    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    
    protected $table = "report_pricing";

    protected $fillable = [
        'report_id', 'license_type', 'file_type', 'price'
    ];
}
