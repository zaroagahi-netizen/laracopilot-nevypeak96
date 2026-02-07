<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Relationships
    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    // Scopes
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}