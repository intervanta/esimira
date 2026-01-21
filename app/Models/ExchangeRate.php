<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exchange_rates';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'currency',
        'rate',
        'last_updated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rate' => 'decimal:8',
        'last_updated' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the exchange rate for a specific currency
     *
     * @param string $currency
     * @return float|null
     */
    public static function getRate(string $currency): ?float
    {
        $rate = static::where('currency', strtoupper($currency))->first();
        return $rate ? (float) $rate->rate : null;
    }

    /**
     * Get all available currencies with their rates
     *
     * @return array
     */
    public static function getAvailableCurrencies(): array
    {
        return static::pluck('currency')->toArray();
    }
}