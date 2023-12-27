<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pages extends Model
{
    use HasFactory;

    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    protected $softCascade = [
        
    ];

    protected $table = "pages";

    protected $fillable = [
         'slug', 'h1', 'meta_title', 'meta_description', 'page_title', 'meta_keyword'
    ];

}
