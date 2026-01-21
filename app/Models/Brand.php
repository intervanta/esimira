<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'device_type_id'];

    public function deviceType(): BelongsTo
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function models(): HasMany
    {
        return $this->hasMany(DeviceModel::class);
    }
}