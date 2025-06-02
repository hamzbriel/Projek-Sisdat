<?php
session_start();
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
            color: #eee;
            margin: 0;
            padding-top: 56px;
            min-height: 100vh;
        }

        p {
            color: #000;
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

        .image-container {
            width: 480px;
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
            border-radius: 8px;
            padding: 1rem;
            color: #a1b0c2;
            max-width: 500px;
            font-size: 0.9rem;
            height: 240px;
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
            background-color: rgb(236, 213, 43);
        }

        .btn-edit:hover {
            background-color: rgb(199, 179, 23);
        }

        .detail-label {
            color: rgb(0, 0, 0);
            font-weight: 600;
        }

        #buyGameModal .modal-game-image {
            max-height: 400px;
            width: 100%;
            height: auto;
            object-fit: contain;
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
        <h1 class="title-small">
            <strong style="color: rgb(5, 5, 5);font-weight: 1000; font-size: 20px"><?php echo $game["nama_game"] ?></strong>
        </h1>
        <div class="d-flex align-items-center gap-4 d-flex-row" style="flex-wrap: wrap;">
            <div class="image-container">
                <img alt="New World Aeternum Season of the Divide game character in dark armor with red glowing accents standing in a misty jungle environment with the game title text on the right"
                    src="../assets/image/<?= $game["gambar"] ?>" />
            </div>
            <div class="details-container">

                <p><span class="margin-woy" style="color:rgb(3, 79, 156); font-weight: 1000; font-size: 20px">Developer : </span><strong style="color: rgb(5, 5, 5);font-weight: 1000; font-size: 20px"><?= $developer["nama_developer"] ?></strong></p>
                <p><span style="color:rgb(3, 79, 156); font-weight: 1000; font-size: 20px">Genre : </span> <strong style="color: rgb(5, 5, 5);font-weight: 1000; font-size: 20px"><?php foreach ($genres as $gen): ?> <?= $gen["genre"] ?> <?php endforeach; ?></strong></p>
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
            <a class="btn btn-primary" href="index.php"><i class="fas fa-arrow-left"></i> Back</a>
            <a href="#"
                class="btn <?= $game_sudah_dibeli ? 'btn-warning btn-download' : 'btn-success btn-buy' ?>"
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
                <?= $game_sudah_dibeli ? 'Download' : 'Buy' ?>
            </a>
        </div>
    </div>

    <!-- Modal Beli Game  Start -->
    <div class="modal fade" id="buyGameModal" tabindex="-1" aria-labelledby="buyGameModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background-color: #555; color: #fff;">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyGameModalLabel">Detail Game</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center" style="gap: 1rem;">
                        <img id="modalGameImage" src="" class="img-fluid" style="max-height: 300px; object-fit: contain;">
                        <h3 id="modalGameName"></h3>
                        <p style="font-weight: bold;">Price: Rp <span id="modalPrice"></span></p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" id="btnConfirmBuy"><i class="fas fa-shopping-cart"></i> Buy Now</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Beli Game  End -->

    <!-- Refresh Halaman -->
    <div id="customAlert" style="
    display: none;
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    padding: 20px 30px;
    border-radius: 10px;
    font-size: 18px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    z-index: 9999;
    text-align: center;
    white-space: pre-line;">
    </div>


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

                // Update href atau data lain jika perlu
                // (Kalau nanti ingin beli otomatis, bisa diatur di sini)

                // Tampilkan modal
                var myModal = new bootstrap.Modal(document.getElementById('buyGameModal'));
                myModal.show();
            });
        });

        // Handle the purchase confirmation
        if (btnConfirm) {
            btnConfirm.addEventListener('click', function() {
                const gameId = document.querySelector('.btn-buy').dataset.gameId; // Get the game ID from the button
                const userId = <?= $id_user ?>; // Get the user ID from PHP
                // Send the purchase request
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
                        // Close the modal
                        var myModalEl = document.getElementById('buyGameModal');
                        var modalInstance = bootstrap.Modal.getInstance(myModalEl);
                        modalInstance.hide();
                        // Optionally, redirect or refresh the page
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
                    window.location.href = 'index.php'; // atau gunakan location.reload();
                }, 3000);
            });
        });
    </script>
</body>

</html>