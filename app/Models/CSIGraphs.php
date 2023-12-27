<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSIGraphs extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = "CSI_graphs";

    protected $fillable = [
        'basemodel_ptr_id', 'graph_type', 'graph_data', 'report_id', 'above_name', 'below_name'
    ];

}