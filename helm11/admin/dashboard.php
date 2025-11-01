<?php
require 'auth_check.php'; // Cek sesi login
require '../config.php';  // Koneksi database

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
        }
        .admin-container { 
            max-width: 1000px; 
            margin: 2rem auto; 
            padding: 2rem; 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .admin-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 2px solid #f4f4f4; 
            padding-bottom: 1rem; 
            margin-bottom: 1rem; 
        }
        .admin-header a {
            text-decoration: none;
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
        }
        h3 { margin-top: 0; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th, td { 
            padding: 12px; 
            border: 1px solid #ddd; 
            text-align: left; 
            vertical-align: middle;
        }
        th { background-color: #f2f2f2; }
        .action-links a { 
            margin-right: 10px; 
            text-decoration: none; 
            font-weight: bold;
        }
        .edit-link { color: #007BFF; }
        .delete-link { color: #dc3545; }
        .btn-add { 
            background: #28a745; 
            color: white; 
            padding: 10px 15px; 
            text-decoration: none; 
            border-radius: 5px; 
            display: inline-block;
            margin-bottom: 1rem;
        }
        img {
            border-radius: 5px;
            max-width: 80px;
            height: auto;
            display: block;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h2>Selamat datang, <?= htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
            <a href="logout.php">Logout</a>
        </div>
        
        <h3>Manajemen Produk</h3>
        <a href="product_add.php" class="btn-add">+ Tambah Produk Baru</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td>
                                <?php 
                                $imgPath = "../" . htmlspecialchars($row['image_url']);
                                if (file_exists($imgPath)): ?>
                                    <img src="<?= $imgPath; ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                                <?php else: ?>
                                    <span style="color:#888;">(gambar tidak ditemukan)</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td>Rp <?= number_format($row['price']); ?></td>
                            <td class="action-links">
                                <a href="product_edit.php?id=<?= $row['id']; ?>" class="edit-link">Edit</a>
                                <a href="product_delete.php?id=<?= $row['id']; ?>" class="delete-link" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">Belum ada produk</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
