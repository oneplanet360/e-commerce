const API_BASE_URL = 'api/';

const api = {
    getToken: () => localStorage.getItem('aura_user_token'),
    getUser: () => JSON.parse(localStorage.getItem('aura_user')),
    getCartSession: () => {
        let sessionId = localStorage.getItem('aura_cart_session');
        if (!sessionId) {
            sessionId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            localStorage.setItem('aura_cart_session', sessionId);
        }
        return sessionId;
    },
    
    headers: () => {
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Cart-Session-ID': api.getCartSession()
        };
        const token = api.getToken();
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }
        return headers;
    },

    get: async (endpoint) => {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            headers: api.headers()
        });
        return await response.json();
    },

    post: async (endpoint, data) => {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            method: 'POST',
            headers: api.headers(),
            body: JSON.stringify(data)
        });
        return await response.json();
    },

    put: async (endpoint, data) => {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            method: 'PUT',
            headers: api.headers(),
            body: JSON.stringify(data)
        });
        return await response.json();
    },

    delete: async (endpoint) => {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            method: 'DELETE',
            headers: api.headers()
        });
        return await response.json();
    },

    getProductPrice: (product) => {
        if (!product) return 0;
        let p = product.price;
        if ((p === null || p === undefined || parseFloat(p) === 0) && product.variants && product.variants.length > 0) {
            return product.variants[0].price || 0;
        }
        return p || 0;
    },

    getProductOldPrice: (product) => {
        if (!product) return null;
        let op = product.discount_price;
        if ((op === null || op === undefined || parseFloat(op) === 0) && product.variants && product.variants.length > 0) {
            return product.variants[0].old_price || null;
        }
        return op || null;
    },

    logout: () => {
        localStorage.removeItem('aura_user_token');
        localStorage.removeItem('aura_user');
        window.location.href = 'login.html';
    },

    formatPrice: (price) => {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            minimumFractionDigits: 2
        }).format(price).replace('₹', 'Rs. ');
    },

    refreshCartUI: async () => {
        try {
            const result = await api.get('cart');
            if (result.success) {
                const cart = result.data;
                const $cartCount = $('#header-cart-count');
                const $cartList = $('.aside-cart-product-list');
                const $cartTotal = $('.cart-total .amount');

                // Update Count
                if (cart.total_quantity > 0) {
                    $cartCount.text(cart.total_quantity).show();
                } else {
                    $cartCount.hide();
                }

                // Update List
                if ($cartList.length) {
                    if (cart.items.length > 0) {
                        const itemsHtml = cart.items.map(item => {
                            const price = item.variant ? item.variant.price : item.product.price;
                            return `
                                <li class="aside-product-list-item">
                                    <a href="javascript:void(0)" class="remove" onclick="api.removeFromCart(${item.id})">×</a>
                                    <a href="product-detail.html?slug=${item.product.slug}">
                                        <img src="${item.product.image_url}" width="68" height="84" alt="${item.product.name}">
                                        <span class="product-title">${item.product.name}</span>
                                    </a>
                                    <span class="product-price">${item.quantity} × ${api.formatPrice(price)}</span>
                                </li>
                            `;
                        }).join('');
                        $cartList.html(itemsHtml);
                        $cartTotal.text(api.formatPrice(cart.total_amount));
                        $('.aside-cart-wrapper .btn-total').show();
                        $('.cart-total').show();
                    } else {
                        $cartList.html('<li class="text-center py-4">Your cart is empty</li>');
                        $('.aside-cart-wrapper .btn-total').hide();
                        $('.cart-total').hide();
                    }
                }
                return cart;
            }
        } catch (e) {
            console.error('Error refreshing cart:', e);
        }
    },

    removeFromCart: async (cartItemId) => {
        if (!confirm('Remove this item?')) return;
        try {
            const result = await api.delete(`cart/remove/${cartItemId}`);
            if (result.success) {
                await api.refreshCartUI();
            }
        } catch (e) {
            console.error('Error removing item:', e);
        }
    },

    buyNow: async (productId, quantity = 1, variantId = null) => {
        if (!api.getToken()) {
            window.location.href = 'login.html';
            return;
        }
        try {
            // Use cart/sync to ensure ONLY this item is in the cart for a direct checkout experience
            const item = { product_id: productId, quantity: quantity };
            if (variantId) item.product_variant_id = variantId;
            
            const result = await api.post('cart/sync', { items: [item] });
            if (result && result.success) {
                window.location.href = 'checkout.html';
            } else {
                alert(result.message || 'Error processing Buy Now');
            }
        } catch (error) {
            console.error('Error in Buy Now:', error);
            alert('An error occurred. Please try again.');
        }
    }
};
