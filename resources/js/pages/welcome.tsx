import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Product, Category, MarketplaceStats, User } from '@/types/marketplace';

interface Props {
    auth?: {
        user?: User;
    };
    featuredProducts?: Product[];
    categories?: Category[];
    stats?: MarketplaceStats;
    [key: string]: unknown;
}

export default function Welcome({ 
    auth, 
    featuredProducts = [], 
    categories = [], 
    stats = { total_products: 0, total_sellers: 0, total_sales: 0 }
}: Props) {
    return (
        <>
            <Head title="Digital Marketplace - Buy & Sell Digital Products" />
            
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
                {/* Navigation */}
                <nav className="bg-white shadow-sm border-b">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center h-16">
                            <div className="flex items-center">
                                <h1 className="text-2xl font-bold text-indigo-600">🛒 DigitalHub</h1>
                            </div>
                            <div className="flex items-center space-x-4">
                                {auth?.user ? (
                                    <>
                                        <Link
                                            href="/dashboard"
                                            className="text-gray-600 hover:text-gray-900"
                                        >
                                            Dashboard
                                        </Link>
                                        <Link
                                            href="/wallet"
                                            className="text-gray-600 hover:text-gray-900"
                                        >
                                            💰 Wallet
                                        </Link>
                                        {(auth.user.role === 'seller' || auth.user.role === 'admin') && (
                                            <Link
                                                href="/products/create"
                                                className="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                                            >
                                                + Sell Product
                                            </Link>
                                        )}
                                    </>
                                ) : (
                                    <>
                                        <Link
                                            href="/login"
                                            className="text-gray-600 hover:text-gray-900"
                                        >
                                            Login
                                        </Link>
                                        <Link
                                            href="/register"
                                            className="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                                        >
                                            Get Started
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </nav>

                {/* Hero Section */}
                <div className="relative overflow-hidden">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                        <div className="text-center">
                            <h1 className="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                                🚀 Digital Product Marketplace
                            </h1>
                            <p className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                                Buy and sell digital products with confidence. From downloadable files to premium accounts,
                                discover thousands of digital products with secure escrow protection and crypto payments.
                            </p>
                            
                            {/* Stats */}
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                                <div className="bg-white rounded-lg p-6 shadow-md">
                                    <div className="text-3xl font-bold text-indigo-600">{stats.total_products.toLocaleString()}</div>
                                    <div className="text-gray-600">📦 Products Available</div>
                                </div>
                                <div className="bg-white rounded-lg p-6 shadow-md">
                                    <div className="text-3xl font-bold text-green-600">{stats.total_sellers.toLocaleString()}</div>
                                    <div className="text-gray-600">👥 Active Sellers</div>
                                </div>
                                <div className="bg-white rounded-lg p-6 shadow-md">
                                    <div className="text-3xl font-bold text-purple-600">{stats.total_sales.toLocaleString()}</div>
                                    <div className="text-gray-600">✅ Successful Sales</div>
                                </div>
                            </div>

                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                {!auth?.user && (
                                    <>
                                        <Link
                                            href="/register"
                                            className="bg-indigo-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-700 shadow-lg"
                                        >
                                            🎯 Start Selling Today
                                        </Link>
                                        <Link
                                            href="#browse"
                                            className="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 shadow-lg border border-indigo-200"
                                        >
                                            🔍 Browse Products
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Features */}
                <div className="bg-white py-16" id="features">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">✨ Why Choose DigitalHub?</h2>
                            <p className="text-xl text-gray-600">Everything you need for secure digital commerce</p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            <div className="text-center p-6">
                                <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span className="text-2xl">🔐</span>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Secure Escrow</h3>
                                <p className="text-gray-600">Protected payments with automatic escrow for account products</p>
                            </div>
                            <div className="text-center p-6">
                                <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span className="text-2xl">₿</span>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Crypto Payments</h3>
                                <p className="text-gray-600">Accept Bitcoin, Ethereum, and USDT deposits</p>
                            </div>
                            <div className="text-center p-6">
                                <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span className="text-2xl">🎁</span>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Referral Program</h3>
                                <p className="text-gray-600">Earn commissions by referring new users to the platform</p>
                            </div>
                            <div className="text-center p-6">
                                <div className="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span className="text-2xl">⚡</span>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Instant Delivery</h3>
                                <p className="text-gray-600">Immediate access to downloadable products after purchase</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Categories */}
                {categories.length > 0 && (
                    <div className="bg-gray-50 py-16" id="browse">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div className="text-center mb-12">
                                <h2 className="text-3xl font-bold text-gray-900 mb-4">🏪 Browse Categories</h2>
                                <p className="text-xl text-gray-600">Find the perfect digital products for your needs</p>
                            </div>

                            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                {categories.map((category) => (
                                    <Link
                                        key={category.id}
                                        href={`/category/${category.slug}`}
                                        className="bg-white rounded-lg p-6 text-center hover:shadow-lg transition-shadow"
                                    >
                                        <div className="text-3xl mb-3">{category.icon || '📁'}</div>
                                        <h3 className="font-semibold text-gray-900">{category.name}</h3>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </div>
                )}

                {/* Featured Products */}
                {featuredProducts.length > 0 && (
                    <div className="bg-white py-16">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div className="text-center mb-12">
                                <h2 className="text-3xl font-bold text-gray-900 mb-4">⭐ Featured Products</h2>
                                <p className="text-xl text-gray-600">Hand-picked premium digital products</p>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                {featuredProducts.slice(0, 8).map((product) => (
                                    <Link
                                        key={product.id}
                                        href={`/products/${product.id}`}
                                        className="bg-white rounded-lg border hover:shadow-lg transition-shadow overflow-hidden"
                                    >
                                        <div className="h-48 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                                        <div className="p-4">
                                            <h3 className="font-semibold text-gray-900 mb-2 truncate">{product.title}</h3>
                                            <p className="text-sm text-gray-600 mb-3 line-clamp-2">{product.description}</p>
                                            <div className="flex items-center justify-between">
                                                <span className="text-lg font-bold text-indigo-600">${product.price}</span>
                                                <span className="text-xs bg-gray-100 px-2 py-1 rounded">
                                                    {product.type === 'downloadable' ? '⬇️ Download' : '👤 Account'}
                                                </span>
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </div>
                )}

                {/* CTA Section */}
                <div className="bg-indigo-600 py-16">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h2 className="text-3xl font-bold text-white mb-4">🚀 Ready to Start Selling?</h2>
                        <p className="text-xl text-indigo-200 mb-8">
                            Join thousands of sellers making money with digital products
                        </p>
                        {!auth?.user && (
                            <Link
                                href="/register"
                                className="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 shadow-lg inline-block"
                            >
                                🎯 Create Seller Account
                            </Link>
                        )}
                    </div>
                </div>

                {/* Footer */}
                <footer className="bg-gray-900 text-white py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div>
                                <h3 className="text-xl font-bold mb-4">🛒 DigitalHub</h3>
                                <p className="text-gray-400">
                                    The trusted marketplace for buying and selling digital products with secure payments and escrow protection.
                                </p>
                            </div>
                            <div>
                                <h4 className="font-semibold mb-4">🛍️ For Buyers</h4>
                                <ul className="space-y-2 text-gray-400">
                                    <li>Browse Products</li>
                                    <li>Secure Payments</li>
                                    <li>Instant Downloads</li>
                                    <li>Buyer Protection</li>
                                </ul>
                            </div>
                            <div>
                                <h4 className="font-semibold mb-4">💼 For Sellers</h4>
                                <ul className="space-y-2 text-gray-400">
                                    <li>List Products</li>
                                    <li>Manage Orders</li>
                                    <li>Withdraw Earnings</li>
                                    <li>Analytics Dashboard</li>
                                </ul>
                            </div>
                            <div>
                                <h4 className="font-semibold mb-4">💎 Premium Features</h4>
                                <ul className="space-y-2 text-gray-400">
                                    <li>Crypto Payments</li>
                                    <li>Referral Program</li>
                                    <li>Escrow Protection</li>
                                    <li>24/7 Support</li>
                                </ul>
                            </div>
                        </div>
                        <div className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                            <p>&copy; 2024 DigitalHub. All rights reserved. Built with ❤️ for the digital economy.</p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}