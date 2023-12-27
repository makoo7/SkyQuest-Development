<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSISubindustrymodel extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "CSI_subindustrymodel";

    protected $fillable = [
        'basemodel_ptr_id', 'title', 'shortcode', 'slug', 'industry_id', 'subcount', 'subcode', 'upcoming_subcount'
    ];

}
