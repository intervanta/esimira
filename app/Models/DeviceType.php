<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // No changes needed - matches your table structure
    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}