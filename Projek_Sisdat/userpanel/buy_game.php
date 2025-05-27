<?php
session_start();
require '../functions.php';
require '../koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Get the game ID from the URL
if (isset($_GET['id'])) {
    $game_id = intval($_GET['id']);
    $game = Query("SELECT * FROM games WHERE game_id = $game_id")[0];
} else {
    header('Location: index.php');
    exit();
}

// Handle purchase logic
if (isset($_POST['purchase'])) {
    // Here you can implement the logic to handle the purchase
    // For example, you might want to insert a record into a purchases table
    // or update the user's library of games.

    // Assuming the purchase is successful
    $successMessage = "Purchase successful! Thank you for buying " . htmlspecialchars($game['nama_game']) . ".";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Buy Game - <?php echo htmlspecialchars($game['nama_game']); ?></title>
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
        .container-content {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(180deg, #f0f5ff 0%, #162f65 100%);
            border-radius: 8px;
            min-height: calc(100vh - 56px - 3rem);
        }
        .image-container {
            width: 100%;
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
                        <a class="nav-link" href="index.php">Home</a>
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
            <strong><?php echo htmlspecialchars($game['nama_game']); ?></strong>
        </h1>
        <div class="image-container">
            <img src="../image/<?php echo htmlspecialchars($game['gambar']); ?>" alt="<?php echo htmlspecialchars($game['nama_game']); ?>" />
        </div>
        <p><strong>Price:</strong> Rp<?php echo htmlspecialchars($game['harga']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($game['deskripsi']); ?></p>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success mt-3" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <button type="submit" name="purchase" class="btn btn-success">Purchase</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>