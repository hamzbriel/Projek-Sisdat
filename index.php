<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gameery</title>
    <link rel="icon" href="assets/icons/logo.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
        .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        .flex-center {
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8 bg-gradient-to-tr from-white via-[#f0f5ff] to-[#162f65]">

        <h1 class="text-[#0f2043] font-extrabold text-4xl sm:text-5xl mb-2 select-none tracking-tight flex-center">
            <img src="assets/icon/logo2.png" alt="Logo" class="logo">
            Gameery
        </h1>
        <h2 class="text-gray-700 font-semibold text-lg sm:text-xl mb-8 select-none">
            Get your dream game
        </h2>

        <div class="flex justify-center">
            <a href="login.php" class="bg-white bg-opacity-90 shadow-lg rounded-xl p-4 max-w-xs w-full block hover:shadow-xl transition-shadow flex items-center justify-center">
                <i class="fas fa-rocket mr-2"></i>
                <h3 class="text-[#0f2043] font-semibold text-base mb-1 select-none">
                    Let's get started
                </h3>
            </a>
        </div>
    </div>
</body>

</html>
