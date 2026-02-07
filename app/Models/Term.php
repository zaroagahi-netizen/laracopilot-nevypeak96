<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Term extends Model
{
    use HasTranslations;

    protected $fillable = [
        'taxonomy_id',
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'taxonomy_id' => 'integer',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function taxonomy()
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'termable');
    }

    // Scopes
    public function scopeByTaxonomy($query, $taxonomySlug)
    {
        return $query->whereHas('taxonomy', function ($q) use ($taxonomySlug) {
            $q->where('slug', $taxonomySlug);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}