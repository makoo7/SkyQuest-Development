<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\AdminNewUserNotification;
use Storage;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard = "admin";

    protected $fillable = [ 'role_id', 'user_name', 'password', 'email', 'phone', 'image',  'is_active' ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->reset_url = route('admin.password.reset', ['token' => $token, 'email' => $this->email]);
        $this->notify(new ResetPasswordNotification($this));
    }

    public function sendNewUserNotification($password)
    {
        $this->new_password = $password;
        $this->notify(new AdminNewUserNotification($this));
    }

    public function getImageUrlAttribute()
    {
        $url = asset("assets/backend/images/default-avatar.png");
        if($this->image) {
            $url = $this->image;
        }
        return $url;
    }

    public function getImageIdAttribute()
    {
        if($this->image) {
            list($imageID) = explode(".", basename($this->image));
            return config('cloudinary.upload_preset').config('constants.ADMIN_PATH').$imageID;
        }
        return;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
