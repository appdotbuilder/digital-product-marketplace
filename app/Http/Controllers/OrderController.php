<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OrderController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['product', 'seller', 'buyer'])
            ->where(function ($query) {
                $query->where('buyer_id', auth()->id())
                      ->orWhere('seller_id', auth()->id());
            })
            ->latest()
            ->paginate(15);
        
        return Inertia::render('orders/index', [
            'orders' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::with('seller')->findOrFail($request->product_id);
        $buyer = auth()->user();
        $buyerWallet = Wallet::firstOrCreate(['user_id' => $buyer->id]);

        // Check if buyer has sufficient balance
        if ($buyerWallet->balance < $product->price) {
            return back()->with('error', 'Insufficient wallet balance.');
        }

        // Check product availability
        if ($product->stock_quantity <= 0) {
            return back()->with('error', 'Product is out of stock.');
        }

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . Str::upper(Str::random(10)),
            'buyer_id' => $buyer->id,
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
            'amount' => $product->price,
            'status' => $product->type === 'account' ? 'pending' : 'processing',
            'escrow_status' => $product->type === 'account' ? 'held' : 'none',
            'product_data' => $product->toArray(),
        ]);

        // Handle escrow for account products
        if ($product->type === 'account') {
            $order->update([
                'escrow_release_at' => now()->addDays(3), // Auto-release after 3 days
            ]);
        }

        // Deduct from buyer's wallet
        $buyerWallet->decrement('balance', $product->price);
        $buyerWallet->increment('total_spent', $product->price);

        // Create buyer transaction
        WalletTransaction::create([
            'user_id' => $buyer->id,
            'transaction_id' => 'TXN-' . Str::upper(Str::random(12)),
            'type' => 'purchase',
            'amount' => -$product->price,
            'balance_after' => $buyerWallet->fresh()->balance,
            'description' => "Purchase of {$product->title}",
            'transactionable_id' => $order->id,
            'transactionable_type' => Order::class,
        ]);

        // Handle immediate payment for downloadable products
        if ($product->type === 'downloadable') {
            $this->completeDownloadableOrder($order, $product);
        }

        // Update product stock
        $product->decrement('stock_quantity');
        $product->increment('sold_count');

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        
        $order->load(['product', 'seller', 'buyer', 'review']);
        
        return Inertia::render('orders/show', [
            'order' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'action' => 'required|in:complete',
        ]);

        $this->authorize('view', $order);
        
        if ($request->action === 'complete') {
            if ($order->escrow_status !== 'held') {
                return back()->with('error', 'Order cannot be completed.');
            }

            // Release escrow to seller
            $sellerWallet = Wallet::firstOrCreate(['user_id' => $order->seller_id]);
            $sellerWallet->increment('balance', $order->amount);
            $sellerWallet->increment('total_earned', $order->amount);

            // Create seller transaction
            WalletTransaction::create([
                'user_id' => $order->seller_id,
                'transaction_id' => 'TXN-' . Str::upper(Str::random(12)),
                'type' => 'sale',
                'amount' => $order->amount,
                'balance_after' => $sellerWallet->fresh()->balance,
                'description' => "Sale of {$order->product_data['title']}",
                'transactionable_id' => $order->id,
                'transactionable_type' => Order::class,
            ]);

            $order->update([
                'status' => 'completed',
                'escrow_status' => 'released',
                'completed_at' => now(),
            ]);

            return back()->with('success', 'Order completed successfully!');
        }

        return back();
    }

    /**
     * Handle completion of downloadable product orders.
     */
    protected function completeDownloadableOrder(Order $order, Product $product): void
    {
        // Immediately pay seller for downloadable products
        $sellerWallet = Wallet::firstOrCreate(['user_id' => $product->user_id]);
        $sellerWallet->increment('balance', $product->price);
        $sellerWallet->increment('total_earned', $product->price);

        // Create seller transaction
        WalletTransaction::create([
            'user_id' => $product->user_id,
            'transaction_id' => 'TXN-' . Str::upper(Str::random(12)),
            'type' => 'sale',
            'amount' => $product->price,
            'balance_after' => $sellerWallet->fresh()->balance,
            'description' => "Sale of {$product->title}",
            'transactionable_id' => $order->id,
            'transactionable_type' => Order::class,
        ]);

        $order->update([
            'status' => 'completed',
            'delivery_data' => [
                'download_url' => $product->download_file,
                'instructions' => 'Click the download link to get your file.',
            ],
            'delivered_at' => now(),
            'completed_at' => now(),
        ]);
    }
}