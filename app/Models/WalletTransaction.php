<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\WalletTransaction
 *
 * @property int $id
 * @property int $user_id
 * @property string $transaction_id
 * @property string $type
 * @property float $amount
 * @property float $balance_after
 * @property string $description
 * @property array|null $metadata
 * @property string $status
 * @property string|null $transactionable_type
 * @property int|null $transactionable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $transactionable
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction query()
 * @method static \Database\Factories\WalletTransactionFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class WalletTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'transaction_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'metadata',
        'status',
        'transactionable_type',
        'transactionable_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent transactionable model (order, crypto deposit, etc.).
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}