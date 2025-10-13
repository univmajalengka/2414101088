<?php
require 'auth_check.php';
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float)$_POST['price'];

    // Folder upload di luar folder admin
    $targetDir = "../uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        $error = "Hanya file JPG, JPEG, PNG, atau GIF yang diperbolehkan.";
    } elseif ($_FILES["image"]["size"] > 9000000) {
        $error = "Ukuran gambar terlalu besar (maks 9MB).";
    } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $error = "Gagal mengupload gambar.";
    } else {
        // Simpan path yang relatif dari index.php
        $imagePath = "uploads/" . $fileName;

        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $imagePath);

        if ($stmt->execute()) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Gagal menambahkan produk.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="form-container">
    <h2>Tambah Produk Baru</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Nama Produk:</label>
        <input type="text" name="name" required>
        <label>Deskripsi:</label>
        <textarea name="description" rows="4" required></textarea>
        <label>Harga:</label>
        <input type="number" step="0.01" name="price" required>
        <label>Upload Gambar:</label>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Tambah Produk</button>
    </form>
</div>
</body>
</html>
