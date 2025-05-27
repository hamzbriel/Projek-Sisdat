<?php
ob_start();
session_start();
require "koneksi.php";

// Set default active tab
$activeTab = 'admin';

// Proses login admin
if (isset($_POST['loginBtnAdmin'])) {
    $activeTab = 'admin'; // Set tab aktif ke admin
    $username = htmlspecialchars($_POST['usernameAdmin']);
    $password = htmlspecialchars($_POST['passwordAdmin']);

    $query = mysqli_query($con, "SELECT * FROM developers WHERE username = '$username'");
    $countData = mysqli_num_rows($query);
    $data = mysqli_fetch_array($query);

    if ($countData > 0) {
        if (password_verify($password, $data['password'])) {
            $_SESSION['usernameAdmin'] = $data['username'];
            $_SESSION['login'] = true;
            ob_end_clean();
            header('location: adminpanel/index.php');
            exit();
        } else {
            $adminError = "Password salah";
        }
    } else {
        $adminError = "Username salah";
    }
}

// Proses login user
if (isset($_POST['loginBtnUser'])) {
    $activeTab = 'user'; // Set tab aktif ke user
    $username = htmlspecialchars($_POST['usernameUser']);
    $password = htmlspecialchars($_POST['passwordUser']);

    $query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
    $countData = mysqli_num_rows($query);
    $data = mysqli_fetch_array($query);

    if ($countData > 0) {
        if (password_verify($password, $data['password_hash'])) {
            $_SESSION['usernameUser'] = $data['username'];
            $_SESSION['login'] = true;
            ob_end_clean();
            header('location: userpanel/index.php');
            exit();
        } else {
            $userError = "Password salah";
        }
    } else {
        $userError = "Username salah";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Halaman Login</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<style>
    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .login-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 0.5rem;
    }

    .login-subtitle {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 1.5rem;
    }
</style>

<body data-active-tab="<?php echo $activeTab; ?>">

    <!-- Form Login Start -->
    <div class="login-body flex-column">
        <div class="login-header">
            <h1 class="login-title">Gameery</h1>
        </div>
        <div class="card p-4 bg-dark text-white">

            <ul class="nav nav-tabs mb-4" id="loginTab" nama="loginTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-white <?php echo ($activeTab === 'admin') ? 'active' : ''; ?>"
                        id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab"
                        aria-controls="admin" aria-selected="<?php echo ($activeTab === 'admin') ? 'true' : 'false'; ?>">
                        Developer
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-white<?php echo ($activeTab === 'user') ? 'active' : ''; ?>"
                        id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab"
                        aria-controls="user" aria-selected="<?php echo ($activeTab === 'user') ? 'true' : 'false'; ?>">
                        User
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="loginTabContent">
                <!-- Admin Tab -->
                <div class="tab-pane fade <?php echo ($activeTab === 'admin') ? 'show active' : ''; ?>"
                    id="admin" role="tabpanel" aria-labelledby="admin-tab">
                    <h3 class="mb-4 text-center">Developer Login</h3>
                    <form action="" method="post" novalidate>
                        <div class="mb-3">
                            <label for="usernameAdmin" class="form-label">Username</label>
                            <input type="text" class="form-control" name="usernameAdmin" id="usernameAdmin"
                                placeholder="Enter username" autocomplete="username" required />
                        </div>
                        <div class="mb-3">
                            <label for="passwordAdmin" class="form-label">Password</label>
                            <input type="password" class="form-control" name="passwordAdmin" id="passwordAdmin"
                                placeholder="Enter password" autocomplete="current-password" required />
                        </div>
                        <button type="submit" name="loginBtnAdmin" class="btn btn-login w-100 py-2">Login</button>
                    </form>
                    <?php if (isset($adminError)): ?>
                        <div class="alert alert-warning mt-3" role="alert">
                            <?php echo $adminError; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- User Tab -->
                <div class="tab-pane fade <?php echo ($activeTab === 'user') ? 'show active' : ''; ?>"
                    id="user" role="tabpanel" aria-labelledby="user-tab">
                    <h3 class="mb-4 text-center">User Login</h3>
                    <form action="" method="post" novalidate>
                        <div class="mb-3">
                            <label for="usernameUser" class="form-label">Username</label>
                            <input type="text" class="form-control" name="usernameUser" id="usernameUser"
                                placeholder="Enter username" />
                        </div>
                        <div class="mb-3">
                            <label for="passwordUser" class="form-label">Password</label>
                            <input type="password" class="form-control" name="passwordUser" id="passwordUser"
                                placeholder="Enter password" />
                        </div>
                        <button type="submit" name="loginBtnUser" class="btn btn-login w-100 py-2">Login</button>
                    </form>
                    <?php if (isset($userError)): ?>
                        <div class="alert alert-warning mt-3" role="alert">
                            <?php echo $userError; ?>
                        </div>
                    <?php endif; ?>
                    <div class="text-center mt-3">
                        <a href="create-account.php" class="text-white no-decoration">Don't have an account? Create One</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
<?php ob_end_flush(); ?>