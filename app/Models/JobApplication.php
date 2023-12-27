<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = "job_applications";

    protected $fillable = [
        'career_id', 'first_name', 'last_name', 'email', 'phone', 'work_experience', 'notice_period', 'current_ctc', 'expected_ctc', 'resume', 'extension', 'portfolio_or_web'
    ];

    public function getNameAttribute()
    {
        $name = ucwords($this->first_name);
        if($this->last_name) {
            $name .= ' '.ucwords($this->last_name);
        }
        return $name;
    }

    public function career()
    {
        return $this->belongsTo(Career::class,'career_id');
    }

}
