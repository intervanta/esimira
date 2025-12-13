<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand_id', 'has_esim'];

    protected $casts = [
        'has_esim' => 'boolean'
    ];

    // Accessor for compatibility (maps has_esim to is_compatible)
    public function getIsCompatibleAttribute()
    {
        return $this->has_esim;
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function exceptions(): HasMany
    {
        return $this->hasMany(DeviceException::class);
    }

    // Scope for compatible devices
    public function scopeCompatible($query)
    {
        return $query->where('has_esim', true);
    }

    // Scope for searching
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('brand', function($brandQuery) use ($searchTerm) {
                  $brandQuery->where('name', 'LIKE', "%{$searchTerm}%");
              });
        });
    }
}