<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportFaq extends Model
{
    use HasFactory;

    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    
    protected $table = "report_faq";

    protected $fillable = [
        'report_id', 'faq_question', 'faq_answer'
    ];
}
