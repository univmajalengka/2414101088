
<?php
// 1. Mulai atau lanjutkan sesi yang sudah ada
session_start();

// 2. Hapus semua variabel sesi
// Ini akan mengosongkan array $_SESSION
$_SESSION = array();

// 3. Hancurkan sesi
// Ini akan menghapus data sesi dari server
session_destroy();

// 4. Alihkan (redirect) pengguna kembali ke halaman utama
// Path '../index.php' berarti "naik satu level direktori, lalu buka index.php"
header("Location: ../index.php");
exit; // Pastikan tidak ada kode lain yang dieksekusi setelah redirect
?>
