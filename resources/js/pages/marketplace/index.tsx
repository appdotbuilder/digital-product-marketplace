import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Product, Category, MarketplaceStats } from '@/types/marketplace';

interface Props {
    featuredProducts: Product[];
    categories: Category[];
    recentProducts: Product[];
    stats: MarketplaceStats;
    [key: string]: unknown;
}

export default function MarketplaceIndex({ 
    featuredProducts, 
    categories, 
    recentProducts, 
    stats 
}: Props) {
    return (
        <AppShell>
            <Head title="Marketplace" />
            
            <div className="space-y-8">
                {/* Hero Stats */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center">
                            <div className="p-3 bg-indigo-100 rounded-lg">
                                <span className="text-2xl">📦</span>
                            </div>
                            <div className="ml-4">
                                <h3 className="text-2xl font-bold text-gray-900">{stats.total_products.toLocaleString()}</h3>
                                <p className="text-gray-600">Products Available</p>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center">
                            <div className="p-3 bg-green-100 rounded-lg">
                                <span className="text-2xl">👥</span>
                            </div>
                            <div className="ml-4">
                                <h3 className="text-2xl font-bold text-gray-900">{stats.total_sellers.toLocaleString()}</h3>
                                <p className="text-gray-600">Active Sellers</p>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center">
                            <div className="p-3 bg-purple-100 rounded-lg">
                                <span className="text-2xl">✅</span>
                            </div>
                            <div className="ml-4">
                                <h3 className="text-2xl font-bold text-gray-900">{stats.total_sales.toLocaleString()}</h3>
                                <p className="text-gray-600">Successful Sales</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Categories */}
                {categories.length > 0 && (
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <h2 className="text-xl font-semibold text-gray-900 mb-6">🏪 Browse Categories</h2>
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            {categories.map((category) => (
                                <Link
                                    key={category.id}
                                    href={`/category/${category.slug}`}
                                    className="p-4 border rounded-lg hover:shadow-md transition-shadow text-center"
                                >
                                    <div className="text-3xl mb-2">{category.icon || '📁'}</div>
                                    <h3 className="font-medium text-gray-900">{category.name}</h3>
                                    <p className="text-sm text-gray-600 mt-1">{category.description}</p>
                                </Link>
                            ))}
                        </div>
                    </div>
                )}

                {/* Featured Products */}
                {featuredProducts.length > 0 && (
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center justify-between mb-6">
                            <h2 className="text-xl font-semibold text-gray-900">⭐ Featured Products</h2>
                            <Link 
                                href="/products" 
                                className="text-indigo-600 hover:text-indigo-800 text-sm font-medium"
                            >
                                View all →
                            </Link>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            {featuredProducts.map((product) => (
                                <Link
                                    key={product.id}
                                    href={`/products/${product.id}`}
                                    className="border rounded-lg hover:shadow-md transition-shadow overflow-hidden"
                                >
                                    <div className="h-40 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                                    <div className="p-4">
                                        <h3 className="font-semibold text-gray-900 mb-2 truncate">{product.title}</h3>
                                        <p className="text-sm text-gray-600 mb-3 line-clamp-2">{product.description}</p>
                                        <div className="flex items-center justify-between">
                                            <span className="text-lg font-bold text-indigo-600">${product.price}</span>
                                            <span className="text-xs bg-gray-100 px-2 py-1 rounded">
                                                {product.type === 'downloadable' ? '⬇️ Download' : '👤 Account'}
                                            </span>
                                        </div>
                                        <div className="mt-2 text-xs text-gray-500">
                                            by {product.seller.name}
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </div>
                )}

                {/* Recent Products */}
                {recentProducts.length > 0 && (
                    <div className="bg-white rounded-lg p-6 shadow-sm">
                        <h2 className="text-xl font-semibold text-gray-900 mb-6">🆕 Recently Added</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {recentProducts.slice(0, 9).map((product) => (
                                <Link
                                    key={product.id}
                                    href={`/products/${product.id}`}
                                    className="border rounded-lg hover:shadow-md transition-shadow overflow-hidden"
                                >
                                    <div className="h-32 bg-gradient-to-r from-blue-500 to-teal-600"></div>
                                    <div className="p-4">
                                        <h3 className="font-semibold text-gray-900 mb-1 truncate">{product.title}</h3>
                                        <p className="text-sm text-gray-600 mb-2 line-clamp-2">{product.description}</p>
                                        <div className="flex items-center justify-between">
                                            <span className="text-lg font-bold text-indigo-600">${product.price}</span>
                                            <span className="text-xs bg-gray-100 px-2 py-1 rounded">
                                                {product.type === 'downloadable' ? '⬇️' : '👤'}
                                            </span>
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </div>
                )}

                {/* Quick Actions */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="bg-indigo-50 rounded-lg p-6">
                        <h3 className="text-lg font-semibold text-indigo-900 mb-2">🛍️ Start Shopping</h3>
                        <p className="text-indigo-700 mb-4">
                            Browse thousands of digital products from verified sellers.
                        </p>
                        <Link
                            href="/products"
                            className="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 inline-block"
                        >
                            Browse All Products
                        </Link>
                    </div>
                    <div className="bg-green-50 rounded-lg p-6">
                        <h3 className="text-lg font-semibold text-green-900 mb-2">💼 Become a Seller</h3>
                        <p className="text-green-700 mb-4">
                            Start earning by selling your digital products today.
                        </p>
                        <Link
                            href="/products/create"
                            className="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 inline-block"
                        >
                            List Your Product
                        </Link>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}