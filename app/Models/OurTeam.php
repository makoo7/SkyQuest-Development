<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurTeam extends Model
{
    use HasFactory;

    protected $table = "our_team";

    protected $fillable = [
        'name', 'image', 'designation', 'is_active'
    ];

    public function getImageIdAttribute()
    {
        if($this->image) {
            list($imageID) = explode(".", basename($this->image));
            return config('cloudinary.upload_preset').config('constants.OURTEAM_PATH').$imageID;
        }
        return;
    }

    public function getImageUrlAttribute()
    {
        $url = asset("assets/backend/images/default-avatar.png");
        if($this->image) {
            $url = $this->image;
        }
        return $url;
    }
}
