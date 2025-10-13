<?php
include 'config.php';

// Cek apakah ada ID produk yang dikirim melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Jika tidak ada ID, kembalikan ke halaman utama
    header("Location: index.php");
    exit;
}

$product_id = (int)$_GET['id'];

// Ambil data produk dari database berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Jika produk tidak ditemukan, kembalikan ke halaman utama
    echo "Produk tidak ditemukan.";
    exit;
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS Tambahan Khusus untuk halaman ini */
        .checkout-container {
            max-width: 600px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .product-summary {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .product-summary img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }
        .product-summary h3 {
            margin: 0;
            font-size: 1.1rem;
        }
        .product-summary .price {
            margin-left: auto;
            font-weight: bold;
            font-size: 1.2rem;
            color: #48ea08;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Formulir Checkout</h2>
        
        <h3>Produk yang Dipesan</h3>
        <div class="product-summary">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div>
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p>Jumlah: 1</p>
            </div>
            <p class="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
        </div>

        <form id="checkout-form">
            <label for="customer_name">Nama Lengkap:</label>
            <input type="text" id="customer_name" name="customer_name" required>

            <label for="customer_phone">No. HP:</label>
            <input type="tel" id="customer_phone" name="customer_phone" required>

            <label for="customer_address">Alamat Lengkap:</label>
            <textarea id="customer_address" name="customer_address" rows="3" required></textarea>

            <label for="payment_method">Metode Pembayaran:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="DANA">DANA</option>
                <option value="BRI">BRI</option>
                <option value="BJB">BJB</option>
            </select>
            
            <p><strong>Total: <span id="total-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span></strong></p>

            <button type="submit">Pesan Sekarang</button>
        </form>
    </div>
    
    <script>
    // Script untuk memproses checkout di halaman ini
    document.getElementById('checkout-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        // Data produk yang dibeli langsung dari PHP
        const productData = {
            id: <?php echo $product['id']; ?>,
            name: "<?php echo htmlspecialchars($product['name']); ?>",
            price: <?php echo $product['price']; ?>,
            quantity: 1
        };

        const formData = new FormData(e.target);
        const orderData = {
            customer_name: formData.get('customer_name'),
            customer_phone: formData.get('customer_phone'),
            customer_address: formData.get('customer_address'),
            payment_method: formData.get('payment_method'),
            cart: [productData] // Kirim produk ini sebagai keranjang
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
                // Arahkan kembali ke halaman utama setelah berhasil
                window.location.href = 'index.php';
            } else {
                alert('Gagal membuat pesanan: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pesanan.');
        }
    });
    </script>
</body>
</html>