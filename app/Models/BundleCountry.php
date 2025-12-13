<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BundleCountry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bundle_countries';

    protected $fillable = ['id', 'name', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * One-to-One relationship: A country has one bundle
     */
     public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'id', 'country_id');
    }

    /**
     * Scope active countries
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope countries that have active bundles
     */
    public function scopeHasBundle($query)
    {
        return $query->whereHas('bundle', function($q) {
            $q->active();
        });
    }

    public function scopeHasActiveBundle($query)
{
    return $query->whereHas('bundle', fn($q) => $q->active());
}
}