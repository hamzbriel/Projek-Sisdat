<?php 
session_start();
if (!isset($_SESSION['usernameAdmin'])) {
    header("Location: ../login.php");
    exit;
}
// var_dump($_SESSION);
require '../functions.php';
$username_developer = $_SESSION["usernameAdmin"];
$developer_id = Query("SELECT developer_id FROM developers WHERE username = '$username_developer';")[0]["developer_id"];
$nama_developer = Query("SELECT nama_developer FROM developers WHERE username='$username_developer'")[0]["nama_developer"];

$data = Query("
SELECT 
u.nama_pengguna AS 'Nama User',
g.nama_game AS 'Nama Game',
GROUP_CONCAT(gen.genre SEPARATOR ', ') AS 'Genre',
g.harga AS 'Harga',
p.tanggal_pembelian AS 'Tanggal Pembelian'
FROM pembelian p
JOIN users u ON p.user_id = u.user_id
JOIN games g ON p.game_id = g.game_id
JOIN genres gen ON g.game_id = gen.game_id
WHERE g.developer_id = '$developer_id'
GROUP BY p.pembelian_id, u.nama_pengguna, g.nama_game, g.harga, p.tanggal_pembelian
ORDER BY p.tanggal_pembelian DESC;;
");
// var_dump($data);
// p.tanggal_pembelian
// DATE(p.tanggal_pembelian)

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Penjualan Game - Admin Panel</title>
    <link rel="icon" href="../assets/icons/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(180deg, #f0f5ff 0%, #162f65 100%);
            color: #eee;
            margin: 0;
            padding-top: 56px;
            min-height: 100vh;
        }
        
        .navbar-custom {
            background-color: #162f65 !important;
        }
        
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #fff;
            font-weight: 600;
        }
        
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #a8c0ff;
        }
        
        .container-content {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(180deg, #f0f5ff 0%, #162f65 100%);
            border-radius: 8px;
            min-height: calc(100vh - 56px - 3rem);
        }
        
        .welcome-text {
            color: #0b2361;
            font-weight: 700;
            font-size: 2rem;
        }
        
        /* PERBAIKAN UTAMA PADA BAGIAN INI */
        .table-custom {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(255, 255, 255, 0.05);
            --bs-table-hover-bg: rgba(255, 255, 255, 0.1);
            color: white;
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .table-custom thead {
            --bs-table-bg:rgb(44, 171, 165);
            color: white;
        }
        
        .table-custom th,
        .table-custom td {
            border-color: rgba(255, 255, 255, 0.1);
            padding: 12px;
        }
    </style>
</head>
<body>
    <!-- Navbar tetap sama -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">
            <img src="../assets/icons/logo.png" alt="Logo" class="logo" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
            Gameery
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
          aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Manage Game</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Game Terjual</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-danger" href="../logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container container-content">
         <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="welcome-text">Selamat datang <?= $nama_developer?>! </h2> 
        </div>

        <!-- Hapus inline style dari tabel -->
        <?php if(empty($data)): ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Belum ada game yang terjual</h4>
                    <!-- <p class="text-muted">Silahkan tambahkan game baru dengan menekan tombol "Tambah Data" di atas</p> -->
                </div>
        <?php else: ?>
        <table class="table table-striped table-custom">
            <thead>
                <tr>
                    <th scope="col" style="color:white;">Nama User</th>
                    <th scope="col"style="color:white;">Nama Game</th>
                    <th scope="col"style="color:white;">Genre</th>
                    <th scope="col"style="color:white;">Harga</th>
                    <th scope="col"style="color:white;">Tanggal Pembelian</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= $row["Nama User"]?></td>
                    <td><?= $row["Nama Game"]?></td>
                    <td><?= $row["Genre"]?></td>
                    <td><?= $row["Harga"]?></td>
                    <td><?= $row["Tanggal Pembelian"]?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>