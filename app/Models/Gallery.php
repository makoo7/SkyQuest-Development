<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = "gallery";

    protected $fillable = [
        'name', 'image'
    ];

    public function getImageIdAttribute()
    {
        if($this->image) {
            list($imageID) = explode(".", basename($this->image));
            return config('cloudinary.upload_preset').config('constants.GALLERY_PATH').$imageID;
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
