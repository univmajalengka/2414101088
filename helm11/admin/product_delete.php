<?php
require 'auth_check.php';
require '../config.php';

$id = (int)$_GET['id'];

if ($id > 0) {
    // Hapus gambar dulu
    $stmt = $conn->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result && file_exists("../" . $result['image_url'])) {
        unlink("../" . $result['image_url']);
    }

    // Hapus data produk
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: dashboard.php');
exit;
?>
