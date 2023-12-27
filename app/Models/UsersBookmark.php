<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersBookmark extends Model
{
    use HasFactory;

    protected $table = "users_bookmark";

    protected $fillable = [
        'user_id', 'entity_type', 'entity_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insights()
    {
        return $this->belongsTo(Insight::class,'entity_id');
    }

    public function casestudies()
    {
        return $this->belongsTo(CaseStudy::class,'entity_id');
    }

    public function reports()
    {
        return $this->belongsTo(Report::class,'entity_id');
    }

}
