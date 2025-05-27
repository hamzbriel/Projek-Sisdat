// index untuk admin (di file 'adminpanel')

<?php
session_start();
// var_dump($_SESSION);

require '../functions.php';
$username_developer = $_SESSION["usernameAdmin"];

$games = Query("SELECT games.* FROM games INNER JOIN developers USING(developer_id) WHERE developers.username = '$username_developer' ");
// var_dump($games);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengelola Barang - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(180deg, #f0f5ff 0%, #162f65 100%);
            color: #eee;
            margin: 0;
            padding-top: 56px; /* height of navbar */
            min-height: 100vh;
        }
        /* Navbar solid background #162f65 */
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
        .card-item {
            background-color: #1e1e1e;
            border-radius: 10px;
            border: none;
            overflow: hidden;
            transition: box-shadow 0.3s ease;
            cursor: default;
            height: 100%;
            display: flex;
            flex-direction: column;
            color: #eee;
        }
        .card-item:hover {
            box-shadow: 0 0 18px rgb(29, 112, 255);
        }
        .card-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
            border-bottom: 1px solid #333;
        }
        .card-body-item {
            padding: 1rem;
            flex-grow: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-name {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .btn-action {
            margin-left: 0.4rem;
        }
        .btn-add {
            background-color: #198754;
            color: #fff;
            font-weight: 600;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            border: none;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-add:hover {
            background-color: #157347;
            color: #fff;
            text-decoration: none;
        }
            .welcome-text {
            color: #0b2361; /* dark blue */
            font-weight: 700;
            font-size: 2rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">Gameery</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
          aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="manage_games.html">Manage Game</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="game_terjual.html">Game Terjual</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-danger" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container container-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="welcome-text">Selamat datang Admin! </h2> 
            <a href="tambah_game.php" class="btn btn-add">Tambah Data</a>
        </div>
        <div class="row g-4">
            <?php if(empty($games)): ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Belum ada game yang ditambahkan</h4>
                    <p class="text-muted">Silahkan tambahkan game baru dengan menekan tombol "Tambah Data" di atas</p>
                </div>
            <?php else: ?>

            <?php foreach ($games as $row) : ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card-item">
                        <img src="image/<?php echo $row["gambar"]; ?>" alt="Item One" />
                        <div class="card-body-item">
                            <div class="item-name"><?php echo $row["nama_game"]; ?></div>
                            <div>
                                <a href="view_game.php?id=<?php echo $row['game_id']; ?>" class="btn btn-outline-primary btn-sm btn-action" title="View" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_game.php?id=<?php echo $row['game_id']; ?>" class="btn btn-outline-warning btn-sm btn-action" title="Edit" target="_blank">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-danger btn-sm btn-action" title="Delete" onclick="confirmDelete('Item One')"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
            <!-- Item 1 -->
            
        </div>
    </div>

    <script>
        function confirmDelete(itemName) {
            if (confirm('Yakin ingin menghapus "' + itemName + '"?')) {
                // Implement delete action here
                alert('Deleted item: ' + itemName);
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
