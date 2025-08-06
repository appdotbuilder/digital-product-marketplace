export interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    icon: string | null;
    is_active: boolean;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

export interface Product {
    id: number;
    user_id: number;
    category_id: number;
    title: string;
    slug: string;
    description: string;
    images: string[] | null;
    price: number;
    type: 'downloadable' | 'account';
    download_file: string | null;
    account_details: string | null;
    is_active: boolean;
    is_featured: boolean;
    stock_quantity: number;
    sold_count: number;
    rating: number;
    review_count: number;
    tags: string[] | null;
    created_at: string;
    updated_at: string;
    seller: User;
    category: Category;
}

export interface User {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'seller' | 'buyer';
    avatar: string | null;
    bio: string | null;
    referral_code: string | null;
    referred_by: number | null;
    is_active: boolean;
    is_verified_seller: boolean;
    created_at: string;
    updated_at: string;
}

export interface Wallet {
    id: number;
    user_id: number;
    balance: number;
    pending_balance: number;
    total_earned: number;
    total_spent: number;
    created_at: string;
    updated_at: string;
}

export interface WalletTransaction {
    id: number;
    user_id: number;
    transaction_id: string;
    type: 'deposit' | 'withdrawal' | 'purchase' | 'sale' | 'referral_bonus' | 'escrow_hold' | 'escrow_release';
    amount: number;
    balance_after: number;
    description: string;
    metadata: Record<string, unknown> | null;
    status: 'pending' | 'completed' | 'failed' | 'cancelled';
    created_at: string;
    updated_at: string;
}

export interface CryptoDeposit {
    id: number;
    user_id: number;
    transaction_hash: string;
    cryptocurrency: string;
    crypto_amount: number;
    usd_amount: number;
    exchange_rate: number;
    wallet_address: string;
    confirmations: number;
    required_confirmations: number;
    status: 'pending' | 'confirmed' | 'failed' | 'expired';
    expires_at: string;
    raw_transaction_data: Record<string, unknown> | null;
    created_at: string;
    updated_at: string;
}

export interface Order {
    id: number;
    order_number: string;
    buyer_id: number;
    seller_id: number;
    product_id: number;
    amount: number;
    status: 'pending' | 'processing' | 'completed' | 'cancelled' | 'refunded' | 'disputed';
    escrow_status: 'none' | 'held' | 'released' | 'refunded';
    escrow_release_at: string | null;
    buyer_notes: string | null;
    seller_notes: string | null;
    admin_notes: string | null;
    product_data: Record<string, unknown>;
    delivery_data: Record<string, unknown> | null;
    delivered_at: string | null;
    completed_at: string | null;
    created_at: string;
    updated_at: string;
    buyer: User;
    seller: User;
    product: Product;
}

export interface MarketplaceStats {
    total_products: number;
    total_sellers: number;
    total_sales: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        path: string;
        per_page: number;
        to: number;
        total: number;
    };
}