<?php 
session_start();
require '../functions.php';

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

$id = $_GET["id"]; // game_id

$game = Query("SELECT * FROM games WHERE game_id=$id")[0];
$developer_id = $game["developer_id"];
$developer = Query("SELECT * FROM developers WHERE developer_id='$developer_id'")[0];
$genres = Query("SELECT genre FROM genres WHERE game_id=$id");
// var_dump($genres);
$list_game_terbeli = Query("SELECT game_id FROM pembelian");
$game_id_terbeli = [];
foreach($list_game_terbeli as $list){
    $game_id_terbeli[] = $list["game_id"];
}

// var_dump($game_id_terbeli);
?>

<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Game - Admin Panel</title>
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
        p {
          color: #000;
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
        .image-container {
            width: 480px; /* medium size */
            aspect-ratio: 2 / 1;
            overflow: hidden;
            border-radius: 8px;
        }
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .details-container {
            /* background-color: #2a3b52; */
            /* border: 1px solid #4a6a8c; */
            border-radius: 8px;
            padding: 1rem;
            color: #a1b0c2;
            max-width: 500px;
            font-size: 0.9rem;
            height: 240px; /* same height as image container */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .details-container strong {
            color: #fff;
            font-weight: 600;
        }
        @media (max-width: 767.98px) {
            .d-flex-row {
                flex-direction: column !important;
            }
            .image-container {
                width: 100%;
                aspect-ratio: auto;
                height: 240px;
                margin-bottom: 1rem;
            }
            .details-container {
                max-width: 100%;
                height: auto;
                justify-content: flex-start;
            }
        }
        .title-small {
            font-size: 1.25rem;
            font-weight: 600;
            color: black;
            border-bottom: 1px solid #4a6a8c;
            padding-bottom: 0.25rem;
            margin-bottom: 1rem;
        }
        .btn-group-bottom {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .btn-custom {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            color: #fff;
        }
        .btn-back {
            background-color: #2563eb;
        }
        .btn-back:hover {
            background-color: #1e40af;
        }
        .btn-edit {
            background-color:rgb(236, 213, 43);
        }
        .btn-edit:hover {
            background-color:rgb(199, 179, 23);
        }
        .detail-label {
            color: rgb(0, 0, 0);
            font-weight: 600;
        }
        .margin-woy{
           
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
                        <a class="nav-link" href="index.php">Manage Game</a>
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
        <h1 class="title-small">
            <strong style="color: rgb(5, 5, 5);font-weight: 1000; font-size: 20px"><?php echo $game["nama_game"] ?></strong>
        </h1>
        <div class="d-flex align-items-center gap-4 d-flex-row" style="flex-wrap: wrap;">
            <div class="image-container">
                <img alt="New World Aeternum Season of the Divide game character in dark armor with red glowing accents standing in a misty jungle environment with the game title text on the right"
                    src="image/<?=$game["gambar"]?>" />
            </div>
            <div class="details-container">
                
                <p><span class="margin-woy" style="color:rgb(3, 79, 156); font-weight: 1000; font-size: 20px">Developer     : </span><strong style="color: rgb(5, 5, 5);font-weight: 1000; font-size: 20px"><?= $developer["nama_developer"] ?></strong></p>
                <p><span style="color:rgb(3, 79, 156); font-weight: 1000; font-size: 20px">Genre : </span> <strong style="color: rgb(5, 5, 5);font-weight: 1000; font-size: 20px"><?php foreach ($genres as $gen): ?> <?= $gen["genre"]?> <?php endforeach; ?></strong></p>
                <p><span style="color:rgb(3, 79, 156); font-weight: 1000; font-size: 20px">Harga : </span> <strong style="color: rgb(5, 5, 5);font-weight: 1000; font-size: 20px">Rp<?php echo $game["harga"] ?></strong></p>
                <p><span style="color:rgb(3, 79, 156); font-weight: 1000; font-size: 20px;">Social Media : </span></p>
                <div class="social-media-item">
                    <span class="social-media-icon" style="color:rgb(0, 0, 0);"><i class="fab fa-instagram"></i></span>
                    <strong style="font-size: 15px">Instagram</strong>
                </div>
                <div class="social-media-item">
                    <span class="social-media-icon" style="color:rgb(0, 0, 0);"><i class="fab fa-twitch"></i></span>
                    <strong style="font-size: 15px">Twitch</strong>
                </div>
                <div class="social-media-item">
                    <span class="social-media-icon" style="color:rgb(0, 0, 0);"><i class="fab fa-youtube"></i></span>
                    <strong style="font-size: 15px">YouTube</strong>
                </div>
                
            </div>
        </div>
        <p class="mb-6 detail-label" style="font-size: 1rem;">
                    <span style="color: rgb(3, 79, 156); font-weight: 600;">
                        Deskripsi
                    </span>
                    <br />
                    <?php echo $game["deskripsi"] ?>
                </p>
     
        <div class="btn-group-bottom">
            <!-- <button class="btn-custom btn-back" type="button">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <a href="index.php" class="btn btn-outline-primary btn-sm btn-action"><i class="fas fa-arrow-left"></i> Back</a> -->
            <a class="btn btn-primary" href="index.php"><i class="fas fa-arrow-left"></i> Back</a>
            <a class="btn btn-warning btn-edit" href="edit_game.php?id=<?php echo $id; ?>"><i class="fas fa-edit"></i> Edit</a>
            <?php if(in_array($id, $game_id_terbeli,true)): ?>
                <button class="btn btn-danger btn-sm btn-action" title="Delete"
                    data-bs-toggle="modal" data-bs-target="#deleteModal2"
                    data-game-id="<?php $id ?>"
                    data-game-name="<?php echo htmlspecialchars($game['nama_game']); ?>">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            <?php else:?>
                <button class="btn btn-danger btn-sm btn-action" title="Delete"
                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                    data-game-id="<?php echo $id; ?>"
                    data-game-name="<?php echo htmlspecialchars($game['nama_game']); ?>">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            <?php endif;?>
            <!-- <a class="btn btn-danger" href="logout.php"><i class="fas fa-trash-alt"></i> Delete</a> -->
            <!-- <button class="btn-custom btn-edit" type="button">
                <i class="fas fa-edit"></i> Edit
            </button> -->
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