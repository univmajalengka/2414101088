<?php
// Pengaturan koneksi database
$db_host = 'localhost';   // Biasanya 'localhost'
$db_user = 'tugaspabw_2414101088';        // User default XAMPP/Laragon
$db_pass = 'aderikinugraha123';            // Password default kosong
$db_name = 'tugaspabw_2414101088';     // Nama database yang sudah dibuat

// Membuat koneksi menggunakan MySQLi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>