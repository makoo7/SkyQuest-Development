<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Career extends Model
{
    use HasFactory,HasSlug;

    protected $fillable = [
        'position', 'slug', 'location', 'exp_range', 'salary_range', 'no_of_position', 'department_id', 'description', 'is_active'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('position')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(255);
    }
}
