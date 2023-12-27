<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'image', 'company_name', 'designation', 'feedback', 'is_active'
    ];

    public function getImageIdAttribute()
    {
        if($this->image) {
            list($imageID) = explode(".", basename($this->image));
            return config('cloudinary.upload_preset').config('constants.CLIENTFEEDBACK_PATH').$imageID;
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
