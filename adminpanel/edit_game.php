<?php 
session_start();
if (!isset($_SESSION['usernameAdmin'])) {
    header("Location: ../login.php");
    exit;
}

require '../functions.php';
$id = $_GET["id"];

$game = Query("SELECT * FROM games WHERE game_id=$id")[0];

$submissionResult = null;
if(isset($_POST["submit"])){
    // cek apakah data berhasil diupdate atau tidak 
    if(Edit($_POST, $_SESSION)>0){// ngejalanin function + cek
        $submissionResult = [
            'success' => true,
            'message' => 'Data berhasil diupdate.'
        ];
    }else{
        $submissionResult = [
            'success' => false,
            'message' => 'Data gagal diupdate.'
        ];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Produk Game</title>
    <link rel="icon" href="../assets/icons/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(180deg, #f0f5ff 0%, #162f65 100%);
            color: #e7e6dd; /* Off-white */
            font-family: 'Poppins', sans-serif;
            padding: 2rem;
            min-height: 100vh;
        }

        .form-container {
            background: linear-gradient(135deg, rgba(10, 25, 55, 0.9) 0%, rgba(90, 110, 150, 0.2) 100%);
            padding: 2rem;
            border-radius: 1rem;
            max-width: 480px;
            margin: auto;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        h2.form-title {
            color: #fff; 
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            text-align: center;
            letter-spacing: 0.1em;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.6);
        }

        label.form-label {
            color: #e7e6dd; 
            font-weight: 600;
        }

        .form-control,
        .form-check-input,
        .form-select,
        textarea.form-control {
            background-color: rgba(10, 25, 55, 0.7); 
            border: 1px solid transparent;
            color: #e7e6dd; 
            transition: border-color 0.3s ease;
            backdrop-filter: blur(6px);
        }

        .form-control::placeholder,
        textarea.form-control::placeholder {
            color: #e7e6ddcc; 
        }

        .form-control:focus,
        .form-check-input:focus,
        .form-select:focus,
        textarea.form-control:focus,
        .form-control:hover,
        .form-check-input:hover,
        .form-select:hover,
        textarea.form-control:hover {
            background-color: rgba(51, 97, 172, 0.8); 
            border: 1px solid #fff !important; 
            box-shadow: 0 0 8px #ffffff !important;
            color: #e7e6dd; 
            outline: none;
            backdrop-filter: blur(6px);
        }

        .form-check-label {
            color: #fff !important;
            font-weight: 500;
            user-select: none;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .form-check-label:hover {
            color: #ffffff;
        }

        #imagePreview {
            max-height: 200px;
            border-radius: 0.5rem;
            border: 1px solid #ffffff;
            margin-top: 0.5rem;
            object-fit: contain;
            width: 100%;
            background: rgba(255 255 255 / 0.05);
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        }

        .btn-submit {
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-submit.btn-success {
            background-color: #198754;
            color: #fff;
            border: none;
        }

        .btn-submit.btn-success:hover {
            background-color: #157347;
            color: #fff;
        }

        .btn-cancel {
            font-weight: 600;
            margin-right: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease;
            background-color: #dc3545;
            color: #fff;
            border: none;
        }

        .btn-cancel:hover {
            background-color: #c82333;
            color: #fff;
        }

        .btn-group-flex {
            display: flex;
            gap: 0.5rem;
        }

        input.form-check-input.is-invalid {
            outline: 2px solid #dc3545;
            box-shadow: 0 0 4px #dc3545;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        /* Modal overlay for purchase feedback */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(22, 47, 101, 0.6); /* Use a transparent dark blue overlay */
            backdrop-filter: blur(6px); /* subtle blur for glass effect */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* Transparent glass-style modal content */
        .modal-content {
            background: rgba(255 255 255 / 0.15); /* translucent white */
            color: #e7e6dd; /* off-white text */
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6); /* stronger shadow for contrast */
            max-width: 420px;
            width: 90%;
            padding: 2rem 2.5rem;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            letter-spacing: 0.05em;
            user-select: none;
            backdrop-filter: blur(12px) saturate(150%);
        }

        /* Adjust modal title and message colors to lighter for better contrast */
        .modal-title {
            font-size: 1.75rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: #f0f5ff; /* light blue-white */
        }

        .modal-message {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            color: #d0d4de; /* lighter off-white */
        }

        /* Adjust icons colors to brighter shades */
        .icon-success {
            color: #22c55e; /* green-500 */
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .icon-failure {
            color: #ef4444; /* red-500 */
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Modal button style with semi-transparent background */
        .modal-button {
            background-color: rgba(37, 99, 235, 0.8);
            color: white;
            padding: 0.7rem 2rem;
            font-size: 1rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.25s ease;
        }

        .modal-button:hover,
        .modal-button:focus {
            background-color: rgba(30, 64, 175, 0.9);
            outline: none;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2 class="form-title">Edit Produk Game</h2>
        <form id="productForm" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="game_id" value="<?= $id; ?>">
            <div class="mb-3">
                <label for="gameName" class="form-label">Nama Game</label>
                <input type="text" class="form-control" id="gameName" name="gameName" placeholder="Masukkan nama game" value="<?= $game["nama_game"];?>"  required />
                <div class="invalid-feedback">Nama game wajib diisi.</div>
            </div>

            <fieldset class="mb-3">
                <legend class="form-label">Genres</legend>
                <div class="form-check form-check-inline">
                    <input class="form-check-input genre-checkbox" type="checkbox" id="genreAction" name="genres[]" value="Action" />
                    <label class="form-check-label" for="genreAction">Action</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input genre-checkbox" type="checkbox" id="genreAdventure" name="genres[]" value="Adventure" />
                    <label class="form-check-label" for="genreAdventure">Adventure</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input genre-checkbox" type="checkbox" id="genreRPG" name="genres[]" value="RPG" />
                    <label class="form-check-label" for="genreRPG">RPG</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input genre-checkbox" type="checkbox" id="genreLainnya" name="genres[]" value="Lainnya" />
                    <label class="form-check-label" for="genreLainnya">Lainnya</label>
                </div>
                <div id="genreError" class="invalid-feedback d-none">
                    Minimal satu genre harus dipilih.
                </div>
            </fieldset>

            <div class="mb-3">
                <label for="price" class="form-label">Harga (Rp)</label>
                <input type="number" class="form-control" id="price" name="price" min="0" value="<?= $game["harga"];?>" placeholder="Masukkan harga" required />
                <div class="invalid-feedback">Harga wajib diisi dan harus berupa angka 0 atau lebih.</div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Game</label>
                <textarea class="form-control" id="description" name="description" placeholder="Masukkan deskripsi game..." required><?= $game["deskripsi"]; ?></textarea>
                <div class="invalid-feedback">Deskripsi game wajib diisi.</div>
            </div>

            <div class="mb-3">
                <label for="productImage" class="form-label">Input Gambar</label>
                <input class="form-control" type="file" id="productImage" name="productImage" accept="image/*" required />
                <img id="imagePreview" alt="Preview Gambar" src="../assets/image/<?= $game['gambar']; ?>" style="display: <?= $game['gambar'] ? 'block' : 'none'; ?>;" />
            </div>

            <div class="btn-group-flex">
                <button type="button" id="cancelButton" class="btn btn-cancel flex-grow-1">Cancel</button>
                <button type="submit" class="btn btn-submit btn-success flex-grow-1" name="submit">Submit</button>
            </div>
        </form>
    </div>

    <?php if ($submissionResult !== null) : ?>
    <div id="submissionModal" class="modal-overlay active" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDescription">
        <div class="modal-content">
            <?php if ($submissionResult['success']) : ?>
                <div class="icon-success" aria-hidden="true">&#10003;</div>
                <h2 id="modalTitle" class="modal-title">Sukses</h2>
            <?php else : ?>
                <div class="icon-failure" aria-hidden="true">&#10007;</div>
                <h2 id="modalTitle" class="modal-title">Gagal</h2>
            <?php endif; ?>
            <p id="modalDescription" class="modal-message"><?= htmlspecialchars($submissionResult['message']) ?></p>
            <button id="modalCloseBtn" class="modal-button" type="button">Tutup</button>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const productImageInput = document.getElementById('productImage');
        const imagePreview = document.getElementById('imagePreview');
        const form = document.getElementById('productForm');
        const cancelButton = document.getElementById('cancelButton');

        productImageInput.addEventListener('change', () => {
            const file = productImageInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
        });

        cancelButton.addEventListener('click', () => {
            window.location.href = 'index.php'; // Redirect on cancel
        });

        form.addEventListener('submit', (event) => {
            const genreCheckboxes = document.querySelectorAll('input[name="genres[]"]:checked');
            const allGenreInputs = document.querySelectorAll('.genre-checkbox');
            const genreError = document.getElementById('genreError');

            if (genreCheckboxes.length === 0) {
                event.preventDefault();

                genreError.classList.remove('d-none');
                genreError.classList.add('d-block');

                allGenreInputs.forEach(cb => cb.classList.add('is-invalid'));
            } else {
                genreError.classList.remove('d-block');
                genreError.classList.add('d-none');

                allGenreInputs.forEach(cb => cb.classList.remove('is-invalid'));
            }
        });

        // Modal close logic and redirect
        const modal = document.getElementById('submissionModal');
        if (modal) {
            const closeBtn = document.getElementById('modalCloseBtn');
            closeBtn.addEventListener('click', () => {
                window.location.href = 'index.php';
            });
            // Optional: auto redirect after 3 seconds
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 3500);
        }
    </script>
</body>

</html>