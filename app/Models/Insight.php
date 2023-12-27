<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Auth;

class Insight extends Model
{
    use HasFactory,HasSlug;

    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    protected $softCascade = [
        
    ];

    protected $table = "insights";

    protected $fillable = [
        'admin_id', 'name', 'image', 'image_alt', 'read_time', 'writer_name', 'writer_image', 'slug', 'meta_title', 'meta_description', 'schema', 'description', 'short_description', 'publish_date', 'is_active'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(255);
    }
    
    public function getImageIdAttribute()
    {
        if($this->image) {
            list($imageID) = explode(".", basename($this->image));
            return config('cloudinary.upload_preset').config('constants.INSIGHT_PATH').$imageID;
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

    public function getWriterImageIdAttribute()
    {
        if($this->writer_image) {
            list($imageID) = explode(".", basename($this->writer_image));
            return config('cloudinary.upload_preset').config('constants.INSIGHT_PATH').$imageID;
        }
        return;
    }

    public function getWriterImageUrlAttribute()
    {
        $url = asset("assets/backend/images/default-avatar.png");
        if($this->writer_image) {
            $url = $this->writer_image;
        }
        return $url;
    }
    
    public function insight_bookmark()
    {
        $user_id = '';
        if(auth('web')->check()){
            $user_id = Auth::user()->id;    
        }
        return $this->hasMany(UsersBookmark::class,'entity_id')->where('entity_type','insight')->where('user_id',$user_id);
    }
}
