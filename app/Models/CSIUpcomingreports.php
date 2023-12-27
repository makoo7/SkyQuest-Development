<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSIUpcomingreports extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = "CSI_upcomingreports";

    protected $fillable = [
        'basemodel_ptr_id', 'report_name', 'description', 'link', 'sc', 'country', 'segments', 'companies', 'report_slug', 'parent_market',
        'img', 'img_alt', 'product_code', 'market_overview', 'growth_rate', 'industry_id', 'industry_group_id', 'sector_id', 'subindustry_id',
        'analyst_support', 'downloads', 'file_type', 'license_type', 'meta_description', 'meta_title', 'methodologies',
        'pages', 'price', 'sample_link', 'sq_schema', 'table_of_content'
    ];

}