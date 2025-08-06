<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CryptoDeposit;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WalletController extends Controller
{
    /**
     * Display the user's wallet.
     */
    public function index()
    {
        $wallet = Wallet::firstOrCreate(['user_id' => auth()->id()]);
        
        $transactions = WalletTransaction::where('user_id', auth()->id())
            ->with('transactionable')
            ->latest()
            ->paginate(20);
        
        $cryptoDeposits = CryptoDeposit::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('wallet/index', [
            'wallet' => $wallet,
            'transactions' => $transactions,
            'cryptoDeposits' => $cryptoDeposits,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:crypto_deposit,confirm_deposit',
            'cryptocurrency' => 'required_if:type,crypto_deposit|in:BTC,ETH,USDT',
            'usd_amount' => 'required_if:type,crypto_deposit|numeric|min:10|max:10000',
            'deposit_id' => 'required_if:type,confirm_deposit|exists:crypto_deposits,id',
        ]);

        if ($request->type === 'crypto_deposit') {
            return $this->createCryptoDeposit($request);
        } else {
            return $this->confirmCryptoDeposit($request);
        }
    }

    /**
     * Create a crypto deposit request.
     */
    protected function createCryptoDeposit(Request $request)
    {
        // Mock exchange rates (in real app, fetch from API)
        $exchangeRates = [
            'BTC' => 45000,
            'ETH' => 3000,
            'USDT' => 1,
        ];

        $crypto = $request->cryptocurrency;
        $usdAmount = $request->usd_amount;
        $rate = $exchangeRates[$crypto];
        $cryptoAmount = $usdAmount / $rate;

        // Generate mock wallet address (in real app, use crypto API)
        $prefixes = [
            'BTC' => ['1', '3', 'bc1'],
            'ETH' => ['0x'],
            'USDT' => ['0x'],
        ];
        $prefix = $prefixes[$crypto][array_rand($prefixes[$crypto])];
        $length = $crypto === 'BTC' ? 34 : 42;
        $walletAddress = $prefix . Str::random($length - strlen($prefix));

        $deposit = CryptoDeposit::create([
            'user_id' => auth()->id(),
            'transaction_hash' => 'pending',
            'cryptocurrency' => $crypto,
            'crypto_amount' => $cryptoAmount,
            'usd_amount' => $usdAmount,
            'exchange_rate' => $rate,
            'wallet_address' => $walletAddress,
            'status' => 'pending',
            'expires_at' => now()->addHours(2),
        ]);

        return back()->with('success', 'Crypto deposit request created. Please send funds to the provided address.');
    }

    /**
     * Simulate crypto deposit confirmation (for demo purposes).
     */
    protected function confirmCryptoDeposit(Request $request)
    {
        $deposit = CryptoDeposit::findOrFail($request->deposit_id);
        
        if ($deposit->user_id !== auth()->id()) {
            abort(403);
        }

        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit cannot be confirmed.');
        }

        // Simulate confirmation
        $deposit->update([
            'status' => 'confirmed',
            'confirmations' => 6,
            'transaction_hash' => 'demo-' . Str::upper(Str::random(20)),
        ]);

        // Add funds to wallet
        $wallet = Wallet::firstOrCreate(['user_id' => auth()->id()]);
        $wallet->increment('balance', $deposit->usd_amount);

        // Create transaction record
        WalletTransaction::create([
            'user_id' => auth()->id(),
            'transaction_id' => 'TXN-' . Str::upper(Str::random(12)),
            'type' => 'deposit',
            'amount' => $deposit->usd_amount,
            'balance_after' => $wallet->fresh()->balance,
            'description' => "Crypto deposit ({$deposit->cryptocurrency})",
            'transactionable_id' => $deposit->id,
            'transactionable_type' => CryptoDeposit::class,
        ]);

        return back()->with('success', 'Crypto deposit confirmed and funds added to your wallet!');
    }
}