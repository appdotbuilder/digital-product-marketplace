import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Wallet, WalletTransaction, CryptoDeposit, PaginatedResponse } from '@/types/marketplace';

interface Props {
    wallet: Wallet;
    transactions: PaginatedResponse<WalletTransaction>;
    cryptoDeposits: CryptoDeposit[];
    [key: string]: unknown;
}

export default function WalletIndex({ wallet, transactions, cryptoDeposits }: Props) {
    const [showDepositModal, setShowDepositModal] = useState(false);
    const [depositForm, setDepositForm] = useState({
        cryptocurrency: 'BTC',
        usd_amount: ''
    });

    const handleCreateDeposit = () => {
        router.post('/wallet', {
            type: 'crypto_deposit',
            ...depositForm
        }, {
            onSuccess: () => {
                setShowDepositModal(false);
                setDepositForm({ cryptocurrency: 'BTC', usd_amount: '' });
            }
        });
    };

    const handleConfirmDeposit = (depositId: number) => {
        router.post('/wallet', {
            type: 'confirm_deposit',
            deposit_id: depositId
        }, {
            preserveState: true,
        });
    };

    const getTransactionIcon = (type: string) => {
        const icons = {
            deposit: '💰',
            withdrawal: '💸',
            purchase: '🛒',
            sale: '💵',
            referral_bonus: '🎁',
            escrow_hold: '🔒',
            escrow_release: '🔓'
        };
        return icons[type as keyof typeof icons] || '💳';
    };

    const getTransactionColor = (type: string) => {
        const colors = {
            deposit: 'text-green-600',
            withdrawal: 'text-red-600',
            purchase: 'text-blue-600',
            sale: 'text-green-600',
            referral_bonus: 'text-purple-600',
            escrow_hold: 'text-yellow-600',
            escrow_release: 'text-green-600'
        };
        return colors[type as keyof typeof colors] || 'text-gray-600';
    };

    return (
        <AppShell>
            <Head title="Wallet" />
            
            <div className="space-y-6">
                {/* Wallet Overview */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center">
                            <div className="p-3 bg-green-100 rounded-lg">
                                <span className="text-2xl">💰</span>
                            </div>
                            <div className="ml-4">
                                <h3 className="text-2xl font-bold text-gray-900">${wallet.balance.toFixed(2)}</h3>
                                <p className="text-gray-600">Available Balance</p>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center">
                            <div className="p-3 bg-yellow-100 rounded-lg">
                                <span className="text-2xl">🔒</span>
                            </div>
                            <div className="ml-4">
                                <h3 className="text-2xl font-bold text-gray-900">${wallet.pending_balance.toFixed(2)}</h3>
                                <p className="text-gray-600">Pending (Escrow)</p>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center">
                            <div className="p-3 bg-blue-100 rounded-lg">
                                <span className="text-2xl">📈</span>
                            </div>
                            <div className="ml-4">
                                <h3 className="text-2xl font-bold text-gray-900">${wallet.total_earned.toFixed(2)}</h3>
                                <p className="text-gray-600">Total Earned</p>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center">
                            <div className="p-3 bg-purple-100 rounded-lg">
                                <span className="text-2xl">💸</span>
                            </div>
                            <div className="ml-4">
                                <h3 className="text-2xl font-bold text-gray-900">${wallet.total_spent.toFixed(2)}</h3>
                                <p className="text-gray-600">Total Spent</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="bg-white rounded-lg p-6 shadow-sm">
                    <h2 className="text-xl font-semibold text-gray-900 mb-4">⚡ Quick Actions</h2>
                    <div className="flex flex-wrap gap-4">
                        <Button
                            onClick={() => setShowDepositModal(true)}
                            className="bg-indigo-600 hover:bg-indigo-700"
                        >
                            ₿ Deposit Crypto
                        </Button>
                        <Button variant="outline">
                            💳 Add Payment Method
                        </Button>
                        <Button variant="outline">
                            📤 Request Withdrawal
                        </Button>
                    </div>
                </div>

                {/* Crypto Deposits */}
                {cryptoDeposits.length > 0 && (
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <h2 className="text-xl font-semibold text-gray-900 mb-4">₿ Recent Crypto Deposits</h2>
                        <div className="space-y-4">
                            {cryptoDeposits.map((deposit) => (
                                <div key={deposit.id} className="flex items-center justify-between p-4 border rounded-lg">
                                    <div className="flex items-center space-x-4">
                                        <div className="text-2xl">₿</div>
                                        <div>
                                            <h4 className="font-medium">{deposit.cryptocurrency} Deposit</h4>
                                            <p className="text-sm text-gray-600">
                                                {deposit.crypto_amount} {deposit.cryptocurrency} → ${deposit.usd_amount}
                                            </p>
                                            <p className="text-xs text-gray-500">
                                                {deposit.status} • {deposit.confirmations}/{deposit.required_confirmations} confirmations
                                            </p>
                                        </div>
                                    </div>
                                    <div className="flex items-center space-x-2">
                                        <span className={`px-2 py-1 text-xs rounded-full ${
                                            deposit.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                            deposit.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            'bg-red-100 text-red-800'
                                        }`}>
                                            {deposit.status}
                                        </span>
                                        {deposit.status === 'pending' && (
                                            <Button
                                                size="sm"
                                                onClick={() => handleConfirmDeposit(deposit.id)}
                                                className="ml-2"
                                            >
                                                Simulate Confirm
                                            </Button>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* Transaction History */}
                <div className="bg-white rounded-lg p-6 shadow-sm">
                    <h2 className="text-xl font-semibold text-gray-900 mb-4">📊 Transaction History</h2>
                    <div className="space-y-4">
                        {transactions.data.map((transaction) => (
                            <div key={transaction.id} className="flex items-center justify-between p-4 border rounded-lg">
                                <div className="flex items-center space-x-4">
                                    <div className="text-2xl">{getTransactionIcon(transaction.type)}</div>
                                    <div>
                                        <h4 className="font-medium">{transaction.description}</h4>
                                        <p className="text-sm text-gray-600">
                                            {new Date(transaction.created_at).toLocaleString()}
                                        </p>
                                        <p className="text-xs text-gray-500">
                                            Balance after: ${transaction.balance_after}
                                        </p>
                                    </div>
                                </div>
                                <div className="text-right">
                                    <span className={`text-lg font-bold ${getTransactionColor(transaction.type)}`}>
                                        {transaction.amount >= 0 ? '+' : ''}${transaction.amount}
                                    </span>
                                    <p className="text-xs text-gray-500 capitalize">{transaction.type.replace('_', ' ')}</p>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Pagination would go here */}
                    {transactions.meta.total === 0 && (
                        <div className="text-center py-8 text-gray-500">
                            <span className="text-4xl">📭</span>
                            <p className="mt-2">No transactions yet</p>
                        </div>
                    )}
                </div>
            </div>

            {/* Crypto Deposit Modal */}
            {showDepositModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 w-full max-w-md">
                        <h3 className="text-xl font-semibold mb-4">₿ Create Crypto Deposit</h3>
                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Cryptocurrency
                                </label>
                                <select
                                    value={depositForm.cryptocurrency}
                                    onChange={(e) => setDepositForm({...depositForm, cryptocurrency: e.target.value})}
                                    className="w-full border rounded-lg px-3 py-2"
                                >
                                    <option value="BTC">Bitcoin (BTC)</option>
                                    <option value="ETH">Ethereum (ETH)</option>
                                    <option value="USDT">Tether (USDT)</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    USD Amount ($10 - $10,000)
                                </label>
                                <input
                                    type="number"
                                    value={depositForm.usd_amount}
                                    onChange={(e) => setDepositForm({...depositForm, usd_amount: e.target.value})}
                                    className="w-full border rounded-lg px-3 py-2"
                                    min="10"
                                    max="10000"
                                    placeholder="Enter amount in USD"
                                />
                            </div>
                        </div>
                        <div className="flex justify-end space-x-3 mt-6">
                            <Button
                                variant="outline"
                                onClick={() => setShowDepositModal(false)}
                            >
                                Cancel
                            </Button>
                            <Button
                                onClick={handleCreateDeposit}
                                disabled={!depositForm.usd_amount || Number(depositForm.usd_amount) < 10}
                            >
                                Create Deposit
                            </Button>
                        </div>
                    </div>
                </div>
            )}
        </AppShell>
    );
}