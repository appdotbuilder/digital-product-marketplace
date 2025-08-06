import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Category } from '@/types/marketplace';

interface Props {
    categories: Category[];
    [key: string]: unknown;
}

export default function ProductsCreate({ categories }: Props) {
    const [formData, setFormData] = useState({
        category_id: '',
        title: '',
        description: '',
        price: '',
        type: 'downloadable',
        download_file: '',
        account_details: '',
        stock_quantity: '1',
        tags: [] as string[],
    });

    const [tagInput, setTagInput] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        router.post('/products', formData, {
            onFinish: () => setIsSubmitting(false),
        });
    };

    const addTag = () => {
        if (tagInput && !formData.tags.includes(tagInput)) {
            setFormData({
                ...formData,
                tags: [...formData.tags, tagInput],
            });
            setTagInput('');
        }
    };

    const removeTag = (tagToRemove: string) => {
        setFormData({
            ...formData,
            tags: formData.tags.filter(tag => tag !== tagToRemove),
        });
    };

    return (
        <AppShell>
            <Head title="Create Product" />
            
            <div className="max-w-4xl mx-auto">
                <div className="mb-8">
                    <h1 className="text-2xl font-bold text-gray-900">📦 Create New Product</h1>
                    <p className="text-gray-600">List your digital product for sale</p>
                </div>

                <form onSubmit={handleSubmit} className="bg-white rounded-lg p-6 shadow-sm space-y-6">
                    {/* Basic Information */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Product Title *
                            </label>
                            <input
                                type="text"
                                value={formData.title}
                                onChange={(e) => setFormData({...formData, title: e.target.value})}
                                className="w-full border rounded-lg px-3 py-2"
                                placeholder="Enter product title"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Category *
                            </label>
                            <select
                                value={formData.category_id}
                                onChange={(e) => setFormData({...formData, category_id: e.target.value})}
                                className="w-full border rounded-lg px-3 py-2"
                                required
                            >
                                <option value="">Select a category</option>
                                {categories.map((category) => (
                                    <option key={category.id} value={category.id}>
                                        {category.icon} {category.name}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Description *
                        </label>
                        <textarea
                            value={formData.description}
                            onChange={(e) => setFormData({...formData, description: e.target.value})}
                            className="w-full border rounded-lg px-3 py-2 h-32"
                            placeholder="Describe your product..."
                            required
                        />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Price (USD) *
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                value={formData.price}
                                onChange={(e) => setFormData({...formData, price: e.target.value})}
                                className="w-full border rounded-lg px-3 py-2"
                                placeholder="0.00"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Product Type *
                            </label>
                            <select
                                value={formData.type}
                                onChange={(e) => setFormData({...formData, type: e.target.value})}
                                className="w-full border rounded-lg px-3 py-2"
                                required
                            >
                                <option value="downloadable">⬇️ Downloadable File</option>
                                <option value="account">👤 Account/Service</option>
                            </select>
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Stock Quantity *
                            </label>
                            <input
                                type="number"
                                min="1"
                                value={formData.stock_quantity}
                                onChange={(e) => setFormData({...formData, stock_quantity: e.target.value})}
                                className="w-full border rounded-lg px-3 py-2"
                                required
                            />
                        </div>
                    </div>

                    {/* Product Type Specific Fields */}
                    {formData.type === 'downloadable' && (
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Download File Path *
                            </label>
                            <input
                                type="text"
                                value={formData.download_file}
                                onChange={(e) => setFormData({...formData, download_file: e.target.value})}
                                className="w-full border rounded-lg px-3 py-2"
                                placeholder="/downloads/your-file.zip"
                                required
                            />
                            <p className="text-sm text-gray-500 mt-1">
                                Enter the path to your downloadable file
                            </p>
                        </div>
                    )}

                    {formData.type === 'account' && (
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Account Details *
                            </label>
                            <textarea
                                value={formData.account_details}
                                onChange={(e) => setFormData({...formData, account_details: e.target.value})}
                                className="w-full border rounded-lg px-3 py-2 h-24"
                                placeholder="Username: example@email.com&#10;Password: secretpassword&#10;Additional info..."
                                required
                            />
                            <p className="text-sm text-gray-500 mt-1">
                                Account credentials and details (will be shown after purchase)
                            </p>
                        </div>
                    )}

                    {/* Tags */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Tags
                        </label>
                        <div className="flex space-x-2 mb-2">
                            <input
                                type="text"
                                value={tagInput}
                                onChange={(e) => setTagInput(e.target.value)}
                                className="flex-1 border rounded-lg px-3 py-2"
                                placeholder="Enter a tag"
                                onKeyPress={(e) => e.key === 'Enter' && (e.preventDefault(), addTag())}
                            />
                            <Button type="button" onClick={addTag} variant="outline">
                                Add Tag
                            </Button>
                        </div>
                        <div className="flex flex-wrap gap-2">
                            {formData.tags.map((tag) => (
                                <span
                                    key={tag}
                                    className="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-sm flex items-center"
                                >
                                    {tag}
                                    <button
                                        type="button"
                                        onClick={() => removeTag(tag)}
                                        className="ml-1 text-indigo-600 hover:text-indigo-800"
                                    >
                                        ×
                                    </button>
                                </span>
                            ))}
                        </div>
                    </div>

                    {/* Submit Button */}
                    <div className="flex justify-end space-x-3 pt-6">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => window.history.back()}
                        >
                            Cancel
                        </Button>
                        <Button
                            type="submit"
                            className="bg-indigo-600 hover:bg-indigo-700"
                            disabled={isSubmitting}
                        >
                            {isSubmitting ? 'Creating...' : '🚀 Create Product'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppShell>
    );
}