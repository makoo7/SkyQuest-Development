<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSIReports extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = "CSI_reports";

    protected $fillable = [
        'basemodel_ptr_id', 'report_name', 'is_upcoming', 'img', 'img_alt', 'sector_id', 'industry_group_id', 'industry_id', 'subindustry_id', 'market', 'role', 'country', 'report_data', 'product_code', 'downloads', 'report_slug', 'meta_title', 'meta_description', 'pages', 'methodologies', 'analyst_support', 'description_1', 'description_2', 'description_3',
        'description_4', 'description_5', 'description_6', 'description_7', 'whats_included', 'table_of_content', 'publish_date', 'sc'
    ];

}