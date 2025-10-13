document.addEventListener('DOMContentLoaded', () => {
    const cartButton = document.getElementById('checkout-button');
    const modal = document.getElementById('checkout-modal');
    const closeButton = document.querySelector('.close-button');
    const buyButtons = document.querySelectorAll('.buy-btn');
    const cartCountSpan = document.getElementById('cart-count');
    const checkoutForm = document.getElementById('checkout-form');
    const cartSummaryDiv = document.getElementById('cart-summary');
    const totalPriceSpan = document.getElementById('total-price');

    let cart = [];

    // --- Fungsi Keranjang ---
    function addToCart(id, name, price) {
        const existingProductIndex = cart.findIndex(item => item.id === id);
        if (existingProductIndex > -1) {
            cart[existingProductIndex].quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        updateCartDisplay();
    }
    
    function updateCartDisplay() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCountSpan.textContent = totalItems;
    }

    function updateModalSummary() {
        cartSummaryDiv.innerHTML = '';
        let totalPrice = 0;
        if (cart.length === 0) {
            cartSummaryDiv.innerHTML = '<p>Keranjang Anda kosong.</p>';
        } else {
            cart.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.innerHTML = `<p>${item.name} x ${item.quantity} - Rp ${Number(item.price * item.quantity).toLocaleString('id-ID')}</p>`;
                cartSummaryDiv.appendChild(itemDiv);
                totalPrice += item.price * item.quantity;
            });
        }
        totalPriceSpan.textContent = `Rp ${Number(totalPrice).toLocaleString('id-ID')}`;
    }

    // --- Event Listeners ---
    buyButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const id = e.target.dataset.id;
            const name = e.target.dataset.name;
            const price = parseFloat(e.target.dataset.price);
            addToCart(id, name, price);
            alert(`${name} telah ditambahkan ke keranjang!`);
        });
    });

    // Buka dan tutup modal untuk keranjang utama
    cartButton.addEventListener('click', () => {
        updateModalSummary();
        modal.style.display = 'block';
    });

    closeButton.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target == modal) {
            modal.style.display = 'none';
        }
    });

    // --- Proses Checkout untuk Keranjang Utama ---
    checkoutForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (cart.length === 0) {
            alert('Keranjang Anda kosong!');
            return;
        }

        const formData = new FormData(checkoutForm);
        const orderData = {
            customer_name: formData.get('customer_name'),
            customer_phone: formData.get('customer_phone'),
            customer_address: formData.get('customer_address'),
            payment_method: formData.get('payment_method'),
            cart: cart
        };

        try {
            const response = await fetch('checkout_process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            });
            const result = await response.json();

            if (result.success) {
                alert('Pesanan berhasil dibuat! Terima kasih.');
                cart = []; // Kosongkan keranjang
                updateCartDisplay();
                modal.style.display = 'none';
                checkoutForm.reset();
            } else {
                alert('Gagal membuat pesanan: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pesanan.');
        }
    });
});