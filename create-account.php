<?php
require "koneksi.php";

$queryUsers = mysqli_query($con, "SELECT * FROM users");
$jumlahUser = mysqli_num_rows($queryUsers);

if (isset($_POST["btnCreate"])) {
    $nama = htmlspecialchars($_POST['name']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Hash password sebelum disimpan
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $queryExist = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
    $jumlahDataUsernameBaru = mysqli_num_rows($queryExist);

    if ($jumlahDataUsernameBaru > 0) {
        $errorMessage = "Username sudah tersedia";
    } else {
        $querySimpan = mysqli_query($con, "INSERT INTO users (username, password_hash, nama_pengguna) VALUES ('$username', '$passwordHash', '$nama')");

        if ($querySimpan) {
            $successMessage = "Berhasil membuat akun";
            header("Refresh: 2; url=login.php");
        } else {
            $errorMessage = "Terjadi kesalahan: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create Account</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        h3 {
            text-align: center;
            margin: 24px 0;
            letter-spacing: 0.03em;
            color: white;
        }

        label {
            color: white !important;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="login-body">
        <div class="card-create p-4">
            <h3>Create Account</h3>
            <form action="" method="post" id="createAccountForm" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Type your name" />
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Choose a username" />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" />
                </div>
                <button type="submit" name="btnCreate" class="btn btn-create w-100 py-2">Create Account</button>
            </form>
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success mt-3" role="alert">
                    <?php echo $successMessage; ?>
                    <div class="spinner-border spinner-border-sm ms-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>

</html>