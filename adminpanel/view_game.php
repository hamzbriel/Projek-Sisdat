<?php 
session_start();
if (!isset($_SESSION['usernameAdmin'])) {
    header("Location: ../login.php");
    exit;
}
require '../functions.php';

// menghapus game
if (isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    Hapus($game_id);
    header("Location: index.php");
    exit();
}

$id = $_GET["id"]; // game_id

$game = Query("SELECT * FROM games WHERE game_id=$id")[0];
$developer_id = $game["developer_id"];
$developer = Query("SELECT * FROM developers WHERE developer_id='$developer_id'")[0];
$genres = Query("SELECT genre FROM genres WHERE game_id=$id");
$list_game_terbeli = Query("SELECT game_id FROM pembelian");
$game_id_terbeli = [];
foreach($list_game_terbeli as $list){
    $game_id_terbeli[] = $list["game_id"];
}
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding-top: 76px;
            min-height: 100vh;
            color: #333;
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: #162f65 !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #fff !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .navbar-custom .nav-link:hover {
            color: #a8c0ff !important;
            transform: translateY(-1px);
        }

        /* Main Container */
        .container-content {
            margin-top: 2rem;
            margin-bottom: 2rem;
            padding: 0;
        }

        /* Game Card */
        .game-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Header Section */
        .game-header {
            background: linear-gradient(180deg, #f0f5ff 0%, #162f65 100%);
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        .game-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx=".66" cy=".4" r=".96"><stop offset="0" stop-color="rgba(255,255,255,.3)"/><stop offset="1" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle fill="url(%23a)" cx="10" cy="10" r="6"/></svg>') repeat;
            opacity: 0.1;
        }
        .game-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }

        /* Content Section */
        .game-content {
            padding: 2rem;
        }

        /* Image and Details Layout */
        .game-showcase {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .image-container {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            aspect-ratio: 16/9;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .image-container:hover img {
            transform: scale(1.05);
        }

        /* Game Details */
        .game-details {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(22, 47, 101, 0.1);
        }

        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(22, 47, 101, 0.1);
        }
        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(180deg, #f0f5ff 0%, #162f65 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            font-size: 1.1rem;
        }

        .detail-content {
            flex: 1;
        }
        .detail-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .detail-value {
            font-size: 1.1rem;
            color: #333;
            font-weight: 700;
        }

        /* Price Styling */
        .price-value {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.3rem;
            font-weight: 800;
        }

        /* Social Media Section */
        .social-media {
            margin-top: 1rem;
        }
        .social-item {
            display: inline-flex;
            align-items: center;
            background: rgba(22, 47, 101, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            margin: 0.25rem;
            color: #162f65;
            font-weight: 600;
            transition: all 0.3s ease;
            gap: 0.5rem !important;
        }
        .social-item:hover {
            background: rgba(22, 47, 101, 0.2);
            transform: translateY(-2px);
        }
        .social-item i {
            margin-right: 0 !important;
            font-size: 1.1rem;
            width: auto;
            flex-shrink: 0;
        }

        /* Description Section */
        .description-section {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(22, 47, 101, 0.1);
        }
        .description-title {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .description-title .detail-icon {
            margin-right: 1rem;
        }
        .description-title h3 {
            margin: 0;
            color: #333;
            font-weight: 700;
        }
        .description-text {
            line-height: 1.6;
            color: #555;
            font-size: 1rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            padding: 1rem 0;
        }
        .btn-custom {
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }
        .btn-edit {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: white;
        }
        .btn-delete {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .game-showcase {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .game-title {
                font-size: 2rem;
            }
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            .btn-custom {
                width: 100%;
                justify-content: center;
                max-width: 300px;
            }
        }

        /* Modal Improvements */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .modal-header .btn-close {
            filter: invert(1);
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
        <div class="game-card">
            <!-- Header Section -->
            <div class="game-header">
                <h1 class="game-title"><?php echo htmlspecialchars($game["nama_game"]) ?></h1>
            </div>

            <!-- Content Section -->
            <div class="game-content">
                <!-- Image and Details Grid -->
                <div class="game-showcase">
                    <div class="image-container">
                        <img src="../assets/image/<?=$game["gambar"]?>" 
                             alt="<?php echo htmlspecialchars($game["nama_game"]) ?>" />
                    </div>
                    
                    <div class="game-details">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-code"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Developer</div>
                                <div class="detail-value"><?= htmlspecialchars($developer["nama_developer"]) ?></div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Genre</div>
                                <div class="detail-value">
                                    <?php 
                                    $genre_list = [];
                                    foreach ($genres as $gen) {
                                        $genre_list[] = $gen["genre"];
                                    }
                                    echo implode(", ", $genre_list);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Harga</div>
                                <?php if($game["harga"] != 0): ?>
                                        <div class="detail-value price-value">Rp<?php echo number_format($game["harga"], 0, ',', '.') ?></div>
                                <?php else: ?>
                                        <div class="detail-value price-value">Free</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-share-alt"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Social Media</div>
                                <div class="social-media">
                                    <span class="social-item">
                                        <i class="fab fa-instagram"></i>
                                        Instagram
                                    </span>
                                    <span class="social-item">
                                        <i class="fab fa-twitch"></i>
                                        Twitch
                                    </span>
                                    <span class="social-item">
                                        <i class="fab fa-youtube"></i>
                                        YouTube
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="description-section">
                    <div class="description-title">
                        <div class="detail-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>Deskripsi Game</h3>
                    </div>
                    <div class="description-text">
                        <?php echo nl2br(htmlspecialchars($game["deskripsi"])) ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="index.php" class="btn-custom btn-back">
                        <i class="fas fa-arrow-left"></i> 
                        Kembali
                    </a>
                    <a href="edit_game.php?id=<?php echo $id; ?>" class="btn-custom btn-edit">
                        <i class="fas fa-edit"></i> 
                        Edit Game
                    </a>
                    <?php if(in_array($id, $game_id_terbeli, true)): ?>
                        <button class="btn-custom btn-delete" 
                                data-bs-toggle="modal" data-bs-target="#deleteModal2"
                                data-game-id="<?php echo $id ?>"
                                data-game-name="<?php echo htmlspecialchars($game['nama_game']); ?>">
                            <i class="fas fa-trash-alt"></i> 
                            Hapus Game
                        </button>
                    <?php else: ?>
                        <button class="btn-custom btn-delete" 
                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                data-game-id="<?php echo $id; ?>"
                                data-game-name="<?php echo htmlspecialchars($game['nama_game']); ?>">
                            <i class="fas fa-trash-alt"></i> 
                            Hapus Game
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal (untuk game yang bisa dihapus) -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img src="../assets/icons/delete3.png" alt="Delete Icon" style="width: 80px; height: 80px;">
                    </div>
                    <p style="color:black;">Apakah Anda yakin ingin menghapus game "<span id="gameNameToDelete"></span>"?</p>
                    <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" action="index.php" method="POST" style="display: inline;">
                        <input type="hidden" name="game_id" id="gameIdToDelete" value="">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal (untuk game yang tidak bisa dihapus) -->
    <div class="modal fade" id="deleteModal2" tabindex="-1" aria-labelledby="deleteModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel2">Tidak Dapat Menghapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img src="../assets/icons/sad_emoji2.png" alt="Sad Emoji" style="width: 80px; height: 80px;">
                    </div>
                    <p style="color:black;">Maaf, Anda tidak dapat menghapus game "<span id="gameNameToDelete2"></span>"!</p>
                    <p class="text-danger">Game tersebut sudah terbeli, lakukan refund terlebih dahulu!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal untuk game yang bisa dihapus
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var gameId = button.getAttribute('data-game-id');
                var gameName = button.getAttribute('data-game-name');
                
                document.getElementById('gameNameToDelete').textContent = gameName;
                document.getElementById('gameIdToDelete').value = gameId;
            });

            // Modal untuk game yang tidak bisa dihapus
            var deleteModal2 = document.getElementById('deleteModal2');
            deleteModal2.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var gameName = button.getAttribute('data-game-name');
                
                document.getElementById('gameNameToDelete2').textContent = gameName;
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>