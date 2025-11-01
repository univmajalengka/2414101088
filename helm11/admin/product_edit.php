<?php
require 'auth_check.php';
require '../config.php';

$id = (int)$_GET['id'];
if ($id <= 0) {
    header('Location: dashboard.php'); exit;
}

// --- PERBAIKAN DIMULAI DI SINI ---

// Ambil data produk lama
// 1. Ubah SELECT * menjadi kolom spesifik yang dibutuhkan
$stmt = $conn->prepare("SELECT name, description, price, image_url FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 2. Ganti get_result() dengan store_result()
$stmt->store_result();

// 3. Cek num_rows untuk melihat apakah produk ditemukan
if ($stmt->num_rows === 0) {
    header('Location: dashboard.php'); exit;
}

// 4. Bind kolom hasil ke variabel PHP (sesuai urutan SELECT)
$stmt->bind_result($name, $description, $price, $image_url);

// 5. Ambil data ke dalam variabel yang di-bind
$stmt->fetch();

// 6. Buat array $product secara manual agar sisa kode HTML bisa berfungsi
$product = [
    'name' => $name,
    'description' => $description,
    'price' => $price,
    'image_url' => $image_url
];

// 7. Tutup statement ini (penting sebelum membuat statement baru di bawah)
$stmt->close();

// --- PERBAIKAN SELESAI ---


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float)$_POST['price'];
    $imagePath = $product['image_url']; // default: tetap gambar lama

    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes) && move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = "uploads/" . $fileName;
        }
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image_url=? WHERE id=?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $imagePath, $id);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Gagal mengupdate produk.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="form-container">
    <h2>Edit Produk</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Nama Produk:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
        <label>Deskripsi:</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($product['description']); ?></textarea>
        <label>Harga:</label>
        <input type="number" step="0.01" name="price" value="<?= $product['price']; ?>" required>
        <label>Gambar Saat Ini:</label>
        <img src="../<?= htmlspecialchars($product['image_url']); ?>" width="120" style="margin-bottom:10px;">
        <label>Upload Gambar Baru (opsional):</label>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Update Produk</button>
    </form>
</div>
</body>
</html>