<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSISectormodel extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = "CSI_sectormodel";

    protected $fillable = [
        'basemodel_ptr_id', 'title', 'code', 'slug', 'is_active'
    ];

}
