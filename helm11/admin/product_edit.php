<?php
require 'auth_check.php';
require '../config.php';

$id = (int)$_GET['id'];
if ($id <= 0) {
    header('Location: dashboard.php'); exit;
}

// Ambil data produk lama
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) {
    header('Location: dashboard.php'); exit;
}

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
