<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Bundle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bundle_type',
        'type',
        'name',
        'title',
        'slug',
        'description',
        'coverage',
        'region',
        'availability',
        'ip_location',
        'privacy_ip',
        'data_validity',
        'currency_code',
        'currency_sign',
        'base_price',
        'coverage_list',
        'regions_list',
        'is_region',
        'region_id',
        'is_country',
        'country_id',
        'image',
        'is_global',
        'is_lifetime',
        'is_popular',
        'is_gcc',
        'status',
        'cached_cheapest_price', // Make sure this is included
    ];

    protected $casts = [
        'is_global' => 'boolean',
        'status' => 'boolean',
        'cached_cheapest_price' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saved(function ($bundle) {
            // Update cached price whenever bundle is saved
            $bundle->updateCachedPrice();
        });

        static::deleted(function ($bundle) {
            // Clear cache when bundle is deleted
            Cache::forget('popular_countries');
            Cache::forget('plan_data_local');
            Cache::forget('plan_data_regional');
            Cache::forget('plan_data_global');
            Cache::forget('plan_data_gcc');
            Cache::forget('plan_data_monthly');
        });
    }

    public function updateCachedPrice()
    {
        $cheapestRefill = $this->refills()
            ->active()
            ->orderBy('sale_price')
            ->first();

        $cheapestPrice = $cheapestRefill ? $cheapestRefill->sale_price : null;
        
        // Update without triggering events to avoid infinite loop
        $this->withoutEvents(function () use ($cheapestPrice) {
            $this->update(['cached_cheapest_price' => $cheapestPrice]);
        });
    }

    public function country()
    {
        return $this->belongsTo(BundleCountry::class, 'country_id');
    }

    public function region()
    {
        return $this->belongsTo(BundleRegion::class, 'region_id');
    }

    public function refills()
    {
        return $this->hasMany(Refill::class);
    }
    public function network()
    {
        return $this->hasMany(Network::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByType($query, $type)
    {
        $map = [
            'local'    => ['is_country' => 1],
            'regional' => ['is_region' => 1],
            'global'   => ['is_global' => 1],
            'gcc'      => ['is_gcc' => 1],
            'monthly'  => ['is_lifetime' => 1],
        ];

        $conditions = $map[$type] ?? [];
        return $query->where($conditions);
    }

    // Cheapest refill price accessor with fallback
    public function getCheapestPriceAttribute()
    {
        // First try cached price
        if ($this->cached_cheapest_price !== null) {
            return $this->cached_cheapest_price;
        }

        // Then try to calculate from refills
        $cheapestRefill = $this->refills()->active()->orderBy('sale_price')->first();
        if ($cheapestRefill) {
            return $cheapestRefill->sale_price;
        }

        // Finally fallback to base_price
        return $this->base_price;
    }

    // Ensure we always have a currency sign
    public function getCurrencySignAttribute($value)
    {
        return $value ?? '$';
    }
}