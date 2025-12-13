<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceException extends Model
{
    use HasFactory;

    protected $fillable = ['device_model_id', 'exception'];

    public function deviceModel(): BelongsTo
    {
        return $this->belongsTo(DeviceModel::class);
    }

    // Accessor for description (maps exception to description)
    public function getDescriptionAttribute()
    {
        return $this->exception;
    }
}