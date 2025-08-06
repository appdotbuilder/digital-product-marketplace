<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\CryptoDeposit
 *
 * @property int $id
 * @property int $user_id
 * @property string $transaction_hash
 * @property string $cryptocurrency
 * @property float $crypto_amount
 * @property float $usd_amount
 * @property float $exchange_rate
 * @property string $wallet_address
 * @property int $confirmations
 * @property int $required_confirmations
 * @property string $status
 * @property \Illuminate\Support\Carbon $expires_at
 * @property array|null $raw_transaction_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WalletTransaction> $transactions
 * @property-read int|null $transactions_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoDeposit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoDeposit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoDeposit query()
 * @method static \Database\Factories\CryptoDepositFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class CryptoDeposit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'transaction_hash',
        'cryptocurrency',
        'crypto_amount',
        'usd_amount',
        'exchange_rate',
        'wallet_address',
        'confirmations',
        'required_confirmations',
        'status',
        'expires_at',
        'raw_transaction_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'crypto_amount' => 'decimal:8',
        'usd_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:8',
        'confirmations' => 'integer',
        'required_confirmations' => 'integer',
        'expires_at' => 'datetime',
        'raw_transaction_data' => 'array',
    ];

    /**
     * Get the user that made the deposit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet transactions for this deposit.
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(WalletTransaction::class, 'transactionable');
    }
}