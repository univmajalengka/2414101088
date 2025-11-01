<?php
include 'config.php';
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Helm Keren</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Toko Helm Riken</div>
            <div class="nav-buttons">
                <a href="/admin/" class="admin-btn">👤 Admin</a>
                <button id="checkout-button">🛒 Keranjang (<span id="cart-count">0</span>)</button>
            </div>
        </nav>
    </header>


<main>
    <h1>Produk Pilihan Kami</h1>
    <div class="product-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
         
<div class="product-card">
    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
    <h2><?php echo htmlspecialchars($row['name']); ?></h2>
    <p class="price">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
    <p><?php echo htmlspecialchars($row['description']); ?></p>

    <a href="beli_langsung.php?id=<?php echo $row['id']; ?>" class="buy-now-btn">
        Beli Langsung
    </a>
    
    <button class="buy-btn"
        data-id="<?php echo $row['id']; ?>"
        data-name="<?php echo htmlspecialchars($row['name']); ?>"
        data-price="<?php echo $row['price']; ?>">
        + Tambah ke Keranjang
    </button>

    </div>
<?php //... (lanjutan kode) ?>
        <?php endwhile; ?>
    </div>
</main>



    <div id="checkout-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Formulir Checkout</h2>
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

                <h3>Ringkasan Pesanan</h3>
                <div id="cart-summary"></div>
                <p><strong>Total: <span id="total-price"></span></strong></p>

                <button type="submit">Pesan Sekarang</button>
            </form>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
