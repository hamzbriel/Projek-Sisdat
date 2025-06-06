<?php
session_start();
// var_dump($_SESSION);

require '../functions.php';

// Ambil semua genre yang tersedia untuk filter
$genres = Query("SELECT DISTINCT genre FROM genres");
// Handle search dan filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';

// menghapus game
if (isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    Hapus($game_id);
    // var_dump($game_id);
    // Lakukan query untuk menghapus game dari database
    // Query("DELETE FROM games WHERE game_id = '$game_id'");
    // Redirect atau refresh halaman setelah penghapusan
    header("Location: index.php");
    exit();
}

$username_developer = $_SESSION["usernameAdmin"];

$query = "SELECT games.* FROM games INNER JOIN developers USING(developer_id) WHERE developers.username = '$username_developer' ";
if (!empty($search)) {
    $query .= " AND games.nama_game LIKE '%$search%'";
}
if (!empty($genre_filter)) {
    $query .= " AND games.game_id IN (SELECT game_id FROM genres WHERE genre = '$genre_filter')";
}

$games = Query($query);
// nama developer
$nama_developer = Query("SELECT nama_developer FROM developers WHERE username='$username_developer'")[0]["nama_developer"];
// var_dump($nama_developer);

// id developer 
$developer_id = Query("SELECT developer_id FROM developers WHERE username = '$username_developer';")[0]["developer_id"];

// mendata game yang sudah terbeli
$list_game_terbeli = Query("SELECT pembelian.game_id FROM pembelian INNER JOIN games USING(game_id) WHERE games.developer_id = '$developer_id' ");
$game_id_terbeli = [];
foreach($list_game_terbeli as $list){
    $game_id_terbeli[] = $list["game_id"];
}

// var_dump($game_id_terbeli);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Game - Admin Panel</title>
    <link rel="icon" href="../assets/icons/logo.png" type="image/png">
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
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-input {
            flex-grow: 1;
            padding: 10px 15px;
            border-radius: 25px;
            border: 1px solid #ced4da;
            outline: none;
        }
        .filter-select {
            padding: 10px 15px;
            border-radius: 25px;
            border: 1px solid #ced4da;
            background-color: white;
            cursor: pointer;
        }
        .search-button {
            padding: 10px 20px;
            border-radius: 25px;
            background-color: #162f65;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-button:hover {
            background-color: #1a3a7a;
        }
    </style>
</head>
<body>
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
              <a class="nav-link" href="#">Manage Game</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="game_terjual.php">Game Terjual</a>
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
            <a href="tambah_game.php" class="btn btn-add">Tambah Data</a>
        </div>

        <!-- Search dan Filter Section -->
        <form method="GET" action="">
            <div class="search-container">
                <input type="text" name="search" class="search-input" placeholder="Search game..." value="<?php echo htmlspecialchars($search); ?>" autocomplete="off">
                <select name="genre" class="filter-select">
                    <option value="">All Genres</option>
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?php echo $genre['genre']; ?>" <?php echo ($genre_filter == $genre['genre']) ? 'selected' : ''; ?>>
                            <?php echo $genre['genre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>

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
                        <img src="../assets/image/<?php echo $row["gambar"]; ?>" alt="Item One" />
                        <div class="card-body-item">
                            <div class="item-name"><?php echo $row["nama_game"]; ?></div>
                            <div>
                                <a href="view_game.php?id=<?php echo $row['game_id']; ?>" class="btn btn-outline-primary btn-sm btn-action" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_game.php?id=<?php echo $row['game_id']; ?>" class="btn btn-outline-warning btn-sm btn-action" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if(in_array($row["game_id"], $game_id_terbeli,true)): ?>
                                    <button class="btn btn-outline-danger btn-sm btn-action" title="Delete"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal2"
                                        data-game-id="<?php echo $row['game_id']; ?>"
                                        data-game-name="<?php echo htmlspecialchars($row['nama_game']); ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                <?php else:?>
                                    <button class="btn btn-outline-danger btn-sm btn-action" title="Delete"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-game-id="<?php echo $row['game_id']; ?>"
                                        data-game-name="<?php echo htmlspecialchars($row['nama_game']); ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
            <!-- Item 1 -->
            
        </div>
    </div>
    <!-- Delete Confirmation Modal (untuk game yang bisa dihapus) -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel" style="color:black;">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <center>
                        <img src="../assets/icons/delete3.png" alt="">
                    </center>
                    <br>
                    <p style="color:black;">Apakah Anda yakin ingin menghapus game "<span id="gameNameToDelete"></span>"?</p>
                    <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" action="index.php" method="POST" style="display: inline;">
                    <input type="hidden" name="game_id" id="gameIdToDelete" value="gameIdToDelete">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete  (untuk game yang tidak bisa dihapus) -->
      <div class="modal fade" id="deleteModal2" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel" style="color:black;">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <center>
                        <img src="../assets/icons/sad_emoji2.png" alt="">
                    </center>
                    <br>
                    <p style="color:black;">Maaf anda tidak dapat menghapus game "<span id="gameNameToDelete2"></span>"!</p>
                    <p class="text-danger">Game tersebut sudah terbeli, lakukan refund terlebih dahulu! </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        
        // Initialize the modal with game data when delete button is clicked
        //#1
        // document.addEventListener('DOMContentLoaded', function() {
        //     var deleteModal = document.getElementById('deleteModal');
        //     deleteModal.addEventListener('show.bs.modal', function(event) {
        //         var button = event.relatedTarget; // Button that triggered the modal
        //         var gameId = button.getAttribute('data-game-id');
        //         var gameName = button.getAttribute('data-game-name');
                
        //         // Update the modal content
        //         document.getElementById('gameNameToDelete').textContent = gameName;
                
        //         // Set the delete link with the correct ID
        //         var deleteButton = document.getElementById('confirmDeleteButton');
        //         deleteButton.href = 'hapus_game.php?id=' + gameId;
        //     });
        // });

        //#2
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var gameId = button.getAttribute('data-game-id');
                var gameName = button.getAttribute('data-game-name');
        // &nbsp;
        // &nbsp;
                // Update the modal content
                document.getElementById('gameNameToDelete').textContent = gameName;
                document.getElementById('gameIdToDelete').value = gameId; // Set the hidden input value
            });
        })

        document.addEventListener('DOMContentLoaded', function() {
                    var deleteModal = document.getElementById('deleteModal2');
                    deleteModal.addEventListener('show.bs.modal', function(event) {
                        var button = event.relatedTarget; // Button that triggered the modal
                        var gameId = button.getAttribute('data-game-id');
                        var gameName = button.getAttribute('data-game-name');
                
                        // Update the modal content
                        document.getElementById('gameNameToDelete2').textContent = gameName;
            });
        })


    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
