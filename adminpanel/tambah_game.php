<?php 
session_start();
if (!isset($_SESSION['usernameAdmin'])) {
    header("Location: ../login.php");
    exit;
}
require '../functions.php';

if(isset($_POST["submit"])){
    // untuk debug
    // var_dump($_POST);
    // var_dump($_FILES); die;

    // cek apakah data berhasil ditambahkan atau tidak 
    if(Tambah($_POST, $_SESSION)>0){// ngejalanin function + cek
        echo "
            <script>
                alert('data berhasil ditambahkan');
                document.location.href = 'index.php';
            </script>
        ";
    }else{
        echo "
            <script>
                alert('data gagal ditambahkan');
                document.location.href = 'index.php';
            </script>
        ";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Input Produk Game</title>
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
            /* Slightly darker, semi-transparent gradient background */
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
            color: #fff; /* changed to white */
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            text-align: center;
            letter-spacing: 0.1em;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.6);
        }

        label.form-label {
            color: #e7e6dd; /* Off-white */
            font-weight: 600;
        }

        .form-control,
        .form-check-input,
        .form-select,
        textarea.form-control {
            background-color: rgba(10, 25, 55, 0.7); /* darker translucent blue */
            border: 1px solid transparent;
            color: #e7e6dd; /* Off-white */
            transition: border-color 0.3s ease;
            backdrop-filter: blur(6px);
        }

        .form-control::placeholder,
        textarea.form-control::placeholder {
            color: #e7e6ddcc; /* Slightly transparent off-white */
        }

        .form-control:focus,
        .form-check-input:focus,
        .form-select:focus,
        textarea.form-control:focus,
        .form-control:hover,
        .form-check-input:hover,
        .form-select:hover,
        textarea.form-control:hover {
            background-color: rgba(51, 97, 172, 0.8); /* lighter translucent blue */
            border: 1px solid #fff !important; /* White border on hover/focus */
            box-shadow: 0 0 8px #ffffff !important;
            color: #e7e6dd; /* Off-white */
            outline: none;
            backdrop-filter: blur(6px);
        }

        /* Genres labels white */
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
            display: none;
            object-fit: contain;
            width: 100%;
            background: rgba(255 255 255 / 0.05);
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        }

        /* Submit button with Bootstrap success color */
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

    </style>
</head>

<body>
    <div class="form-container">
        <h2 class="form-title">Input Produk Game</h2>
        <form id="productForm" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="gameName" class="form-label">Nama Game</label>
                <input type="text" class="form-control" id="gameName" name="gameName" placeholder="Masukkan nama game"
                    required />
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
                <input type="number" class="form-control" id="price" name="price" min="0" step="1000"
                    placeholder="Masukkan harga" required />
                <div class="invalid-feedback">Harga wajib diisi dan harus berupa angka 0 atau lebih.</div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Game</label>
                <textarea class="form-control" id="description" name="description" 
                    placeholder="Masukkan deskripsi game..." required></textarea>
                <div class="invalid-feedback">Deskripsi game wajib diisi.</div>
            </div>

            <div class="mb-3">
                <label for="productImage" class="form-label">Input Gambar</label>
                <!-- <input type="file" id="productImage" name="productImage" accept="image/*" required /> -->
                <input class="form-control" type="file" id="productImage" name="productImage" accept="image/*" required />
                <img id="imagePreview" alt="Preview Gambar" />
            </div>

            <div class="btn-group-flex">
                <button type="button" id="cancelButton" class="btn btn-cancel flex-grow-1">Cancel</button>
                <button type="submit" class="btn btn-submit btn-success flex-grow-1" name="submit">Submit</button>
            </div>
        </form>
    </div>

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
                imagePreview.style.display = 'none';
                imagePreview.src = '';
            }
        });

        cancelButton.addEventListener('click', () => {
            // Redirect to another tab/page when cancel is clicked
            window.location.href = 'index.php'; // Change this URL to your desired page
        });

        form.addEventListener('submit', (event) => {
            const genreCheckboxes = document.querySelectorAll('input[name="genres[]"]:checked');
            const allGenreInputs = document.querySelectorAll('.genre-checkbox');
            const genreError = document.getElementById('genreError');

            if (genreCheckboxes.length === 0) {
                event.preventDefault(); // Mencegah form dikirim

                // Tampilkan peringatan
                genreError.classList.remove('d-none');
                genreError.classList.add('d-block');

                // Tambahkan class is-invalid ke semua checkbox
                allGenreInputs.forEach(cb => cb.classList.add('is-invalid'));
            } else {
                // Sembunyikan peringatan
                genreError.classList.remove('d-block');
                genreError.classList.add('d-none');

                // Hapus class is-invalid
                allGenreInputs.forEach(cb => cb.classList.remove('is-invalid'));
            }
        });
    </script>
</body>

</html>