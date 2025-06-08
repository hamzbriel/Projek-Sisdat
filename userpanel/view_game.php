<?php
session_start();
if (!isset($_SESSION['usernameUser'])) {
    header("Location: ../login.php");
    exit;
}
require '../functions.php';

$id = $_GET['id'];

$game = Query("SELECT * FROM games WHERE game_id=$id")[0];
$developer_id = $game["developer_id"];
$developer = Query("SELECT * FROM developers WHERE developer_id='$developer_id'")[0];
$genres = Query("SELECT genre FROM genres WHERE game_id=$id");
$username_user = $_SESSION["usernameUser"];
$id_user = Query("SELECT * FROM users WHERE username = '$username_user'")[0]['user_id'];
$cek_game = Query("SELECT * FROM pembelian WHERE game_id = '$id' AND user_id = '$id_user'");
$game_sudah_dibeli = count($cek_game) > 0;

?>

<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Game - User Panel</title>
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
        }
        .social-item:hover {
            background: rgba(22, 47, 101, 0.2);
            transform: translateY(-2px);
        }
        .social-item i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
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
        .btn-buy {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        .btn-download {
            background: linear-gradient(135deg, #ffc107, #e0a800);
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
            background-color: #fff;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .modal-header .btn-close {
            filter: invert(1);
        }
        
        .modal-body {
            color: #333;
        }
        
        .modal-game-image {
            max-height: 300px;
            width: 100%;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Custom Alert */
        #customAlert {
            display: none;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #28a745, #20c997);
            color: #fff;
            padding: 20px 30px;
            border-radius: 15px;
            font-size: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            z-index: 9999;
            text-align: center;
            white-space: pre-line;
            font-weight: 600;
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
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="library_game.php">Library Game</a>
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
                                <div class="detail-value price-value">Rp<?php echo number_format($game["harga"], 0, ',', '.') ?></div>
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
                    <a href="#"
                        class="btn-custom <?= $game_sudah_dibeli ? 'btn-download' : 'btn-buy' ?>"
                        data-game-id="<?= $game['game_id'] ?>"
                        data-game-name="<?= $game['nama_game'] ?>"
                        data-game-image="../assets/image/<?= $game['gambar'] ?>"
                        data-game-price="<?= number_format($game['harga'], 0, ',', '.') ?>"
                        data-game-developer="<?= $developer['nama_developer'] ?>"
                        data-game-genres="<?php
                                            $g_list = [];
                                            foreach ($genres as $g) {
                                                $g_list[] = $g['genre'];
                                            }
                                            echo implode(', ', $g_list);
                                            ?>"
                        data-game-deskripsi="<?= $game['deskripsi'] ?>">
                        <i class="fas <?= $game_sudah_dibeli ? 'fa-download' : 'fa-shopping-cart' ?>"></i>
                        <?= $game_sudah_dibeli ? 'Download' : 'Beli Game' ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Beli Game -->
    <div class="modal fade" id="buyGameModal" tabindex="-1" aria-labelledby="buyGameModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyGameModalLabel">Detail Game</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center" style="gap: 1rem;">
                        <img id="modalGameImage" src="" class="modal-game-image">
                        <h3 id="modalGameName" style="color: #333; font-weight: 700;"></h3>
                        <p style="font-weight: bold; color: #333; font-size: 1.2rem;">
                            Harga: <span style="color: #28a745;">Rp <span id="modalPrice"></span></span>
                        </p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" id="btnConfirmBuy">
                        <i class="fas fa-shopping-cart"></i> Beli Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Alert -->
    <div id="customAlert"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        // Simpan referensi ke tombol
        const btnConfirm = document.getElementById('btnConfirmBuy');

        document.querySelectorAll('.btn-buy').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const gameId = this.dataset.gameId;
                const gameName = this.dataset.gameName;
                const gameImage = this.dataset.gameImage;
                const gamePrice = this.dataset.gamePrice;

                // Isi data di modal
                document.getElementById('modalGameImage').src = gameImage;
                document.getElementById('modalGameName').textContent = gameName;
                document.getElementById('modalPrice').textContent = gamePrice;

                // Tampilkan modal
                var myModal = new bootstrap.Modal(document.getElementById('buyGameModal'));
                myModal.show();
            });
        });

        // Handle the purchase confirmation
        if (btnConfirm) {
            btnConfirm.addEventListener('click', function() {
                const gameId = document.querySelector('.btn-buy').dataset.gameId;
                const userId = <?= $id_user ?>;
                
                fetch('buy_game.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `game_id=${gameId}&user_id=${userId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Pembelian game berhasil');
                        var myModalEl = document.getElementById('buyGameModal');
                        var modalInstance = bootstrap.Modal.getInstance(myModalEl);
                        modalInstance.hide();
                        window.location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses pembelian');
                });
            });
        }

        document.querySelectorAll('.btn-download').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const gameName = this.dataset.gameName;

                const alertBox = document.getElementById('customAlert');

                // Tampilkan pesan pertama
                alertBox.textContent = `Memulai download game: ${gameName}\nSilakan tunggu...`;
                alertBox.style.display = 'block';

                // Setelah 2 detik, ganti ke pesan sukses
                setTimeout(() => {
                    alertBox.textContent = `Game berhasil didownload!`;
                }, 2000);

                // Setelah total 3 detik, sembunyikan dan redirect
                setTimeout(() => {
                    alertBox.style.display = 'none';
                    window.location.href = 'index.php';
                }, 3000);
            });
        });
    </script>
</body>
</html>