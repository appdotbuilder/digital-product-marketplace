import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Product, PaginatedResponse } from '@/types/marketplace';

interface Props {
    products: PaginatedResponse<Product>;
    [key: string]: unknown;
}

export default function ProductsIndex({ products }: Props) {
    return (
        <AppShell>
            <Head title="My Products" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">📦 My Products</h1>
                        <p className="text-gray-600">Manage your digital products and sales</p>
                    </div>
                    <Link href="/products/create">
                        <Button className="bg-indigo-600 hover:bg-indigo-700">
                            + Add New Product
                        </Button>
                    </Link>
                </div>

                {/* Products Grid */}
                {products.data.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {products.data.map((product) => (
                            <div
                                key={product.id}
                                className="bg-white rounded-lg border hover:shadow-lg transition-shadow overflow-hidden"
                            >
                                <div className="h-40 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                                <div className="p-4">
                                    <div className="flex items-start justify-between mb-2">
                                        <h3 className="font-semibold text-gray-900 truncate flex-1">
                                            {product.title}
                                        </h3>
                                        <span className={`ml-2 px-2 py-1 text-xs rounded-full ${
                                            product.is_active 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-red-100 text-red-800'
                                        }`}>
                                            {product.is_active ? 'Active' : 'Inactive'}
                                        </span>
                                    </div>
                                    
                                    <p className="text-sm text-gray-600 mb-3 line-clamp-2">
                                        {product.description}
                                    </p>
                                    
                                    <div className="flex items-center justify-between mb-3">
                                        <span className="text-lg font-bold text-indigo-600">
                                            ${product.price}
                                        </span>
                                        <span className="text-xs bg-gray-100 px-2 py-1 rounded">
                                            {product.type === 'downloadable' ? '⬇️ Download' : '👤 Account'}
                                        </span>
                                    </div>

                                    <div className="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-4">
                                        <div>📦 Stock: {product.stock_quantity}</div>
                                        <div>💰 Sold: {product.sold_count}</div>
                                        <div>⭐ Rating: {product.rating}/5</div>
                                        <div>📝 Reviews: {product.review_count}</div>
                                    </div>

                                    <div className="flex space-x-2">
                                        <Link
                                            href={`/products/${product.id}`}
                                            className="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded text-sm text-center hover:bg-gray-200"
                                        >
                                            View
                                        </Link>
                                        <Link
                                            href={`/products/${product.id}/edit`}
                                            className="flex-1 bg-indigo-600 text-white px-3 py-2 rounded text-sm text-center hover:bg-indigo-700"
                                        >
                                            Edit
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="text-center py-12 bg-white rounded-lg">
                        <div className="text-6xl mb-4">📦</div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">No products yet</h3>
                        <p className="text-gray-600 mb-6">
                            Start selling by creating your first digital product
                        </p>
                        <Link href="/products/create">
                            <Button className="bg-indigo-600 hover:bg-indigo-700">
                                Create Your First Product
                            </Button>
                        </Link>
                    </div>
                )}
            </div>
        </AppShell>
    );
}