<?php
session_start();
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // 1. Ubah SELECT * menjadi kolom spesifik. Ini praktik yang baik
    //    dan diperlukan untuk bind_result().
    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    // 2. Ganti get_result() dengan store_result()
    $stmt->store_result();

    // 3. Periksa num_rows dari statement itu sendiri
    if ($stmt->num_rows === 1) {
        // 4. Bind kolom hasil ke variabel PHP
        $stmt->bind_result($admin_id, $admin_username, $hashed_password);
        
        // 5. Ambil data yang sudah di-bind
        $stmt->fetch();

        // 6. PERBAIKAN KEAMANAN: Ganti md5() dengan password_verify()
        //    (Sesuai komentar di kode asli Anda)
        if (password_verify($password, $hashed_password)) {
            // Login berhasil
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_username'] = $admin_username;
            header("Location: dashboard.php");
            exit;
        }
    }

    // Jika username tidak ditemukan ATAU password salah
    header("Location: index.php?error=1");
    exit;
}
?>