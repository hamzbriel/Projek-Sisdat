<?php
session_start();
if (!isset($_SESSION['usernameUser'])) {
    header("Location: ../login.php");
    exit;
}

require '../functions.php';
$username_user = $_SESSION["usernameUser"];
$query_nama_user = mysqli_query($con, "SELECT * FROM users WHERE username='$username_user'");
$dataUser = mysqli_fetch_array($query_nama_user);

// Ambil semua genre yang tersedia untuk filter
$genres = Query("SELECT DISTINCT genre FROM genres");

// Handle search dan filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';

// Query dengan filter
$query = "SELECT * FROM games ";
if (!empty($genre_filter)) {
    $query .= " JOIN genres ON games.game_id = genres.game_id";
}

$query .= " WHERE 1=1";
if (!empty($search)) {
    $query .= " AND games.nama_game LIKE '%$search%'";
}
if (!empty($genre_filter)) {
    $query .= " AND genres.genre = '$genre_filter'";
}

$games = Query($query);

$pembelianData = Query("SELECT game_id FROM pembelian WHERE user_id = '$dataUser[user_id]'");

$game_dibeli = [];
foreach ($pembelianData as $pembelian) {
    $game_dibeli[] = $pembelian['game_id'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gameery - Home</title>
    <link rel="icon" href="../assets/icons/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css">
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
            display: block;
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
            color: #0b2361;
            font-weight: 700;
            font-size: 2rem;
        }

        .image-game-modal {
            width: 100%;
            height: auto;
            max-width: 600px;
            object-fit: contain;
        }

        .btn-success .spinner-border {
            vertical-align: middle;
            margin-right: 5px;
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

        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <h1 class="navbar-brand d-flex align-items-center">
                <img src="../assets/icons/logo.png" alt="Logo" class="logo" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                Gameery
            </h1>
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
                        <a class="nav-link me-2" href="library_game.php">Library Game</a>
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
            <h2 class="welcome-text">Welcome <?php echo $dataUser['nama_pengguna']; ?>! </h2>
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
            <?php if (empty($games)): ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Belum ada game yang ditambahkan</h4>
                </div>
            <?php else: ?>
                <!-- Data Game Start -->
                <?php foreach ($games as $row) : ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card-item">
                            <img src="../assets/image/<?php echo $row["gambar"]; ?>" alt="Item One" />
                            <div class="card-body-item">
                                <div class="item-name"><?php echo $row["nama_game"]; ?></div>
                                <div>
                                    <a href="view_game.php?id=<?php echo $row['game_id']; ?>" class="btn btn-outline-primary btn-sm btn-action"><i class="fas fa-eye"></i></a>
                                    <?php if (in_array($row['game_id'], $game_dibeli)): ?>
                                        <!-- Tombol Download -->
                                        <button class="btn btn-outline-info btn-sm btn-action download-btn"
                                            title="Download Game"
                                            data-game-id="<?php echo $row['game_id']; ?>"
                                            data-game-name="<?php echo $row['nama_game']; ?>">
                                            <i class="fa-solid fa-download"></i>
                                        </button>
                                    <?php else: ?>
                                        <!-- Tombol Beli -->
                                        <a href="#"
                                            class="btn btn-outline-success btn-sm btn-action buy-btn"
                                            title="Beli Game"
                                            data-bs-toggle="modal"
                                            data-bs-target="#buyModal"
                                            data-game-id="<?php echo $row['game_id']; ?>"
                                            data-game-name="<?php echo $row['nama_game']; ?>"
                                            data-game-price="<?php echo number_format($row['harga'], 0, ',', '.'); ?>"
                                            data-game-image="<?php echo $row['gambar']; ?>">
                                            <i class="fas fa-cart-shopping"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- Data Game End -->
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Start -->
    <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #555; color: #fff;">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyModalLabel">Anda ingin membeli game ini?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div style="background-color: #888; padding: 10px; border-radius: 8px; display: inline-block;">
                        <img src="" id="modalGameImage" class="image-game-modal" alt="Game">
                        <h5 class="mt-3" id="modalGameName">Game Name</h5>
                        <p><strong>Price:</strong> Rp <span id="modalGamePrice">99.999,99</span></p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmBuy">Buy</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->
    <!-- Alert download -->
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
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi variabel untuk modal pembelian
            const buyModal = new bootstrap.Modal(document.getElementById('buyModal'));
            const modalGameImage = document.getElementById('modalGameImage');
            const modalGameName = document.getElementById('modalGameName');
            const modalGamePrice = document.getElementById('modalGamePrice');
            const confirmBuyBtn = document.getElementById('confirmBuy');
            const userId = <?php echo $dataUser['user_id']; ?>;

            // Handle tombol beli (munculkan modal)
            document.querySelectorAll('.buy-btn').forEach(button => {
                button.addEventListener('click', function() {
                    modalGameImage.src = '../assets/image/' + this.getAttribute('data-game-image');
                    modalGameName.textContent = this.getAttribute('data-game-name');
                    modalGamePrice.textContent = this.getAttribute('data-game-price');
                    confirmBuyBtn.setAttribute('data-game-id', this.getAttribute('data-game-id'));
                });
            });

            // Handle tombol download (dengan pop-up animasi & auto refresh)
            document.querySelectorAll('.download-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const gameName = this.getAttribute('data-game-name');
                    const alertBox = document.getElementById('customAlert');

                    // Tampilkan pesan pertama
                    alertBox.textContent = `Memulai download game: ${gameName}\nSilakan tunggu...`;
                    alertBox.style.display = 'block';

                    // Setelah 2 detik, ubah ke pesan sukses
                    setTimeout(() => {
                        alertBox.textContent = `Game berhasil didownload!`;
                    }, 2000);

                    // Setelah total 3 detik, sembunyikan dan reload halaman
                    setTimeout(() => {
                        alertBox.style.display = 'none';
                        window.location.reload(); // Atau: location.href = 'index.php';
                    }, 3000);
                });
            });

            // Proses pembelian (tetap sama)
            confirmBuyBtn.addEventListener('click', function() {
                const gameId = this.getAttribute('data-game-id');
                const btn = this;

                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
                btn.disabled = true;

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
                            alert('Pembelian berhasil! Halaman akan direfresh...');
                            window.location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses pembelian');
                    })
                    .finally(() => {
                        if (data.status !== 'success') {
                            btn.innerHTML = 'Buy';
                            btn.disabled = false;
                        }
                    });
            });
        });
    </script>
</body>

</html>