<?php
session_start();
require '../functions.php';

if (!isset($_SESSION['usernameUser'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'];
    $user_id = $_POST['user_id'];

    // Cek apakah game sudah dibeli sebelumnya
    $check = mysqli_query($con, "SELECT * FROM pembelian WHERE user_id = '$user_id' AND game_id = '$game_id'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Game sudah dibeli sebelumnya']);
        exit;
    }

    // Insert pembelian baru
    $query = "INSERT INTO pembelian (user_id, game_id) VALUES ('$user_id', '$game_id')";
    $result = mysqli_query($con, $query);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Pembelian berhasil']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal melakukan pembelian']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
