<?php
header('Content-Type: application/json');
include 'config.php';

// Mendapatkan data JSON yang dikirim dari JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Validasi data dasar
if (!$data || !isset($data['customer_name']) || !isset($data['cart']) || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    exit;
}

// Ambil data pelanggan
$customer_name = $conn->real_escape_string($data['customer_name']);
$customer_phone = $conn->real_escape_string($data['customer_phone']);
$customer_address = $conn->real_escape_string($data['customer_address']);
$payment_method = $conn->real_escape_string($data['payment_method']);
$cart = $data['cart'];

// Mulai transaksi database untuk memastikan semua query berhasil
$conn->begin_transaction();

try {
    // 1. Simpan data ke tabel 'orders'
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $customer_name, $customer_phone, $customer_address, $payment_method);
    $stmt->execute();
    
    // Dapatkan ID pesanan yang baru saja dibuat
    $order_id = $conn->insert_id;
    
    // 2. Simpan setiap item di keranjang ke tabel 'order_items'
    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    
    foreach ($cart as $item) {
        $product_id = (int)$item['id'];
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];
        
        $stmt_items->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $stmt_items->execute();
    }
    
    // Jika semua query berhasil, commit transaksi
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Pesanan berhasil disimpan.']);

} catch (mysqli_sql_exception $exception) {
    // Jika ada error, batalkan semua perubahan (rollback)
    $conn->rollback();
    
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan pada database: ' . $exception->getMessage()]);
}

// Tutup statement dan koneksi
$stmt->close();
if (isset($stmt_items)) {
    $stmt_items->close();
}
$conn->close();
?>