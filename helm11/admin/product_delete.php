<?php
require 'auth_check.php';
require '../config.php';

$id = (int)$_GET['id'];

if ($id > 0) {
    // --- PERBAIKAN DIMULAI DI SINI ---
    
    // 1. Ambil nama file gambar dulu
    $stmt = $conn->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // 2. Ganti get_result() dengan store_result()
    $stmt->store_result();
    
    // 3. Bind hasil ke variabel
    $stmt->bind_result($image_path);
    
    // 4. Fetch hasilnya
    $stmt->fetch();
    
    // 5. Cek file dan hapus (gunakan variabel $image_path)
    if ($stmt->num_rows > 0 && $image_path && file_exists("../" . $image_path)) {
        unlink("../" . $image_path);
    }
    
    // 6. Tutup statement pertama
    $stmt->close();

    // --- PERBAIKAN SELESAI ---

    // Hapus data produk dari database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: dashboard.php');
exit;
?>