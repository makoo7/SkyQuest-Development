<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Report extends Model
{
    use HasFactory;

    protected $table = "reports";

    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    protected $softCascade = [
        'report_faq@update',
        'report_graphs@update',
        'report_metrics@update',
        'report_pricing@update',
        'report_segments@update',
        'report_tableofcontent@update',
    ];

    protected $fillable = [
        'report_type', 'name', 'image','sector_id','industry_group_id','industry_id','sub_industry_id','country','product_id','download','image_alt','slug','meta_title','meta_description','pages','parent_market','description','methodologies','analyst_support','market_insights','segmental_analysis','regional_insights','market_dynamics','competitive_landscape','key_market_trends','skyQuest_analysis','whats_included','publish_date','schema','s_c','free_sample_report_link','is_active','created_at','updated_at'
    ];

    public function getImageIdAttribute()
    {
        if($this->image) {
            list($imageID) = explode(".", basename($this->image));
            return config('cloudinary.upload_preset').config('constants.REPORT_PATH').$imageID;
        }
        return;
    }

    public function getImageUrlAttribute()
    {
        $url = asset("assets/backend/images/no-image.png");
        if($this->image) {
            $url = $this->image;
        }
        return $url;
    }

    public function report_faq()
    {
        return $this->hasMany(ReportFaq::class, 'report_id');
    }

    public function report_metrics()
    {
        return $this->hasMany(ReportMetrics::class, 'report_id');
    }

    public function report_pricing()
    {
        return $this->hasMany(ReportPricing::class, 'report_id');
    }

    public function report_segments()
    {
        return $this->hasMany(ReportSegment::class, 'report_id');
    }

    public function report_tableofcontent()
    {
        return $this->hasMany(ReportTableofcontent::class, 'report_id');
    }

    public function report_graphs()
    {
        return $this->hasMany(ReportGraphs::class, 'report_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function industry_group()
    {
        return $this->belongsTo(IndustryGroup::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function sub_industry()
    {
        return $this->belongsTo(SubIndustry::class);
    }

    public function report_bookmark()
    {
        $user_id = '';
        if(auth('web')->check()){
            $user_id = Auth::user()->id;    
        }
        return $this->hasMany(UsersBookmark::class,'entity_id')->where('entity_type','report')->where('user_id',$user_id);
    }
}
