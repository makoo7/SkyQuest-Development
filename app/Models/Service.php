<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Service extends Model
{
    use HasFactory,HasSlug;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    protected $softCascade = [
        
    ];

    protected $fillable = [
        'name', 'image', 'image_alt', 'read_time', 'slug', 'page_title', 'meta_title', 'meta_description', 'description', 'short_description', 'how_it_helps', 'schema', 'is_active'
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
            return config('cloudinary.upload_preset').config('constants.SERVICE_PATH').$imageID;
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
}
