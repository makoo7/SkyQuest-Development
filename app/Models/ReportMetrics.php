<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportMetrics extends Model
{
    use HasFactory;

    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    
    protected $table = "report_metrics";

    protected $fillable = [
        'report_id', 'meta_key', 'meta_value'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
