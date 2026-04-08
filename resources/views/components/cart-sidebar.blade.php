<!-- Floating Cart Sidebar -->
<div id="cartSidebar" class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-shopping-cart"></i> Your Cart
            </h2>
            <button onclick="toggleCartSidebar()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <!-- Cart Items Container -->
        <div class="flex-1 overflow-y-auto p-4" id="cartItemsContainer">
            <div id="cartItemsList">
                <!-- Cart items loaded here -->
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-shopping-cart text-6xl mb-4"></i>
                    <p>Your cart is empty</p>
                    <button onclick="toggleCartSidebar()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Continue Shopping
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Footer with Total and Checkout -->
        <div class="border-t p-4 bg-gray-50" id="cartFooter" style="display: none;">
            <div class="flex justify-between items-center mb-3">
                <span class="font-semibold">Subtotal:</span>
                <span class="text-xl font-bold text-green-600" id="cartSubtotal">$0.00</span>
            </div>
            <div class="flex justify-between items-center mb-3 text-sm text-gray-500">
                <span>Shipping:</span>
                <span>Calculated at checkout</span>
            </div>
            <a href="{{ route('cart.index') }}" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition text-center block mb-2">
                View Cart
            </a>
            <a href="{{ route('checkout.index') }}" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition text-center block">
                Proceed to Checkout
            </a>
        </div>
    </div>
</div>

<!-- Cart Button - Floating Action Button -->
<button onclick="toggleCartSidebar()" class="fixed bottom-8 right-8 bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600 transition z-40">
    <div class="relative">
        <i class="fas fa-shopping-cart text-2xl"></i>
        <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
    </div>
</button>

<!-- Overlay -->
<div id="cartOverlay" onclick="toggleCartSidebar()" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

<script>
// Toggle cart sidebar
function toggleCartSidebar() {
    const sidebar = document.getElementById('cartSidebar');
    const overlay = document.getElementById('cartOverlay');
    
    if (sidebar.classList.contains('translate-x-full')) {
        sidebar.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        loadCartItems();
    } else {
        sidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
    }
}

// Load cart items via AJAX
function loadCartItems() {
    fetch('/cart/ajax', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateCartDisplay(data);
    })
    .catch(error => {
        console.error('Error loading cart:', error);
    });
}

// Update cart display
function updateCartDisplay(cartData) {
    const itemsList = document.getElementById('cartItemsList');
    const footer = document.getElementById('cartFooter');
    const cartCount = document.getElementById('cartCount');
    
    if (cartData.items && cartData.items.length > 0) {
        let itemsHtml = '';
        let subtotal = 0;
        
        cartData.items.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            itemsHtml += `
                <div class="flex items-center gap-3 mb-4 pb-3 border-b">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm">${escapeHtml(item.title)}</h4>
                        <p class="text-xs text-gray-500">$${item.price.toFixed(2)} each</p>
                        <div class="flex items-center gap-2 mt-1">
                            <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300">-</button>
                            <span class="text-sm w-8 text-center">${item.quantity}</span>
                            <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300">+</button>
                            <button onclick="removeFromCart(${item.id})" class="ml-2 text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-semibold text-green-600">$${itemTotal.toFixed(2)}</span>
                    </div>
                </div>
            `;
        });
        
        itemsHtml += `<div class="text-center text-gray-500 text-sm mt-4">
                        <i class="fas fa-truck"></i> Shipping calculated at checkout
                      </div>`;
        
        itemsList.innerHTML = itemsHtml;
        document.getElementById('cartSubtotal').innerText = `$${subtotal.toFixed(2)}`;
        cartCount.innerText = cartData.total_items;
        footer.style.display = 'block';
    } else {
        itemsList.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-shopping-cart text-6xl mb-4"></i>
                <p>Your cart is empty</p>
                <button onclick="toggleCartSidebar()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Continue Shopping
                </button>
            </div>
        `;
        cartCount.innerText = '0';
        footer.style.display = 'none';
    }
}

// Update quantity
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(cartId);
        return;
    }
    
    fetch('/cart/update-ajax', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ cart_id: cartId, quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCartItems();
            updateCartCount();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Remove from cart
function removeFromCart(cartId) {
    fetch('/cart/remove-ajax', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ cart_id: cartId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCartItems();
            updateCartCount();
            showNotification('Item removed from cart', 'info');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Update cart count badge
function updateCartCount() {
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const cartCount = document.getElementById('cartCount');
        if (cartCount) {
            cartCount.innerText = data.count;
        }
    })
    .catch(error => console.error('Error:', error));
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Show notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Load cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});
</script>
