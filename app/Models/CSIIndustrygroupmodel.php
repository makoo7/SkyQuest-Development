<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSIIndustrygroupmodel extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "CSI_industrygroupmodel";

    protected $fillable = [
        'basemodel_ptr_id', 'title', 'shortcode', 'slug', 'sector_id'
    ];

}
