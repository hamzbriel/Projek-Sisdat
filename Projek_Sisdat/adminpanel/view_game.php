// view_game untuk admin (di file 'adminpanel')

<?php 

require '../functions.php';

$id = $_GET["id"];

$game = Query("SELECT * FROM games WHERE game_id=$id")[0];
$developer_id = $game["developer_id"];
$developer = Query("SELECT * FROM developers WHERE developer_id='$developer_id'")[0];
$genres = Query("SELECT genre FROM genres WHERE game_id=$id");
// var_dump($genres);

?>

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
            <a class="btn btn-warning btn-edit" href="logout.php"><i class="fas fa-edit"></i> Edit</a>
            <a class="btn btn-danger" href="logout.php"><i class="fas fa-trash-alt"></i> Delete</a>
            <!-- <button class="btn-custom btn-edit" type="button">
                <i class="fas fa-edit"></i> Edit
            </button> -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>