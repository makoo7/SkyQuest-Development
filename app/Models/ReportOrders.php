<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportOrders extends Model
{
    use HasFactory;
    
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    protected $table = "report_orders";

    protected $fillable = [
        'user_id', 'report_id', 'report_type', 'license_type', 'file_type', 'payment_method', 'payment_status', 'price', 'name', 'email', 'phonecode', 'phone', 'linkedin_link', 'company_name', 'designation', 'country_id', 'message'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
