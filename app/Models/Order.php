<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $order_number
 * @property int $buyer_id
 * @property int $seller_id
 * @property int $product_id
 * @property float $amount
 * @property string $status
 * @property string $escrow_status
 * @property \Illuminate\Support\Carbon|null $escrow_release_at
 * @property string|null $buyer_notes
 * @property string|null $seller_notes
 * @property string|null $admin_notes
 * @property array $product_data
 * @property array|null $delivery_data
 * @property \Illuminate\Support\Carbon|null $delivered_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $buyer
 * @property-read \App\Models\User $seller
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Review|null $review
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WalletTransaction> $transactions
 * @property-read int|null $transactions_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_number',
        'buyer_id',
        'seller_id',
        'product_id',
        'amount',
        'status',
        'escrow_status',
        'escrow_release_at',
        'buyer_notes',
        'seller_notes',
        'admin_notes',
        'product_data',
        'delivery_data',
        'delivered_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'escrow_release_at' => 'datetime',
        'product_data' => 'array',
        'delivery_data' => 'array',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the buyer (user) that made the order.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the seller (user) that owns the product.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the product that was ordered.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the review for this order.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get the wallet transactions for this order.
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(WalletTransaction::class, 'transactionable');
    }
}