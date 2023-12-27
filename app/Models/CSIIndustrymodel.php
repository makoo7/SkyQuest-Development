<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSIIndustrymodel extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "CSI_industrymodel";

    protected $fillable = [
        'basemodel_ptr_id', 'title', 'shortcode', 'slug', 'group_id'
    ];

}
