<?php 
require 'koneksi.php';

function Query($query)
{
    global $con;
    $result = mysqli_query($con, $query);

    $rows = [];     // wadah utk simpan keseluruhan data

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row; // proses penambahan data pada rows
    }

    return $rows;
}

function Tambah($data, $data_session)
{
    global $con;

    // ambil data dari tiap elemen dalam form
    $nama_game = htmlspecialchars($data["gameName"]);
    $harga = htmlspecialchars($data["price"]); 
    $deskripsi = htmlspecialchars($data["description"]);

    // dapetin developer id
    $userNameAdmin = $data_session["usernameAdmin"];
    $developer = Query("SELECT developer_id FROM developers WHERE username='$userNameAdmin'");
    
    // Check if developer exists
    if(empty($developer)) {
        throw new Exception("Developer not found for username: $userNameAdmin");
    }
    
    $developer_id = $developer[0]['developer_id'];

    // upload gambar
    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    // query insert data games
    $query = "INSERT INTO games 
              VALUES ('', '$nama_game', '$harga', '$deskripsi', '$developer_id', '$gambar')";
    mysqli_query($con, $query);

    // Ambil game_id terakhir yang baru di-insert
    $game_id = mysqli_insert_id($con);

    // Dapatkan genres (harus array)
    $genres = $data["genres"]; // Pastikan ini array dari form (misalnya <select multiple>)

    if (!is_array($genres)) {
        $genres = [$genres]; // Jika hanya satu genre
    }

    // Masukkan tiap genre ke tabel genres
    foreach ($genres as $genre) {
        $genre = htmlspecialchars($genre);
        $query = "INSERT INTO genres VALUES ('', '$genre', '$game_id')";
        mysqli_query($con, $query);
    }




    return mysqli_affected_rows($con);
}

function Upload() {
    // Check if image directory exists, if not create it
    $uploadDir = 'image/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $namaFile = $_FILES['productImage']['name'];
    $ukuranFile = $_FILES['productImage']['size'];
    $error = $_FILES['productImage']['error'];
    $tmpName = $_FILES['productImage']['tmp_name'];

    // Check if file was uploaded
    if($error === 4) {
        throw new Exception("Please upload an image");
        return false;
    }

    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    // Generate unique filename
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    $destination = $uploadDir . $namaFileBaru;

    // Move uploaded file
    if(!move_uploaded_file($tmpName, $destination)) {
        throw new Exception("Failed to move uploaded file");
        return false;
    }

    return $namaFileBaru;
}

function Edit($data, $data_session){
    global $con;
    $nama_game = htmlspecialchars($data["gameName"]);
    $harga = htmlspecialchars($data["price"]); 
    $deskripsi = htmlspecialchars($data["description"]);
    $game_id = htmlspecialchars($data["game_id"]);

    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    // query edit data games
    $query = "UPDATE games SET
    nama_game = '$nama_game',
    harga = '$harga',
    deskripsi = '$deskripsi',
    gambar = '$gambar' WHERE game_id='$game_id'";   
    mysqli_query($con, $query);
    
    // menghapus semua genre game ini
    $query_hapus_genre = "DELETE FROM genres WHERE game_id='$game_id'";
    mysqli_query($con, $query_hapus_genre);
    

    // menambahkan genre baru pada game ini
    // Dapatkan genres (harus array)
    $genres = $data["genres"]; // Pastikan ini array dari form (misalnya <select multiple>)

    if (!is_array($genres)) {
        $genres = [$genres]; // Jika hanya satu genre
    }
    // Masukkan tiap genre ke tabel genres
    foreach ($genres as $genre) {
        $genre = htmlspecialchars($genre);
        $query = "INSERT INTO genres VALUES ('', '$genre', '$game_id')";
        mysqli_query($con, $query);
    }

    return mysqli_affected_rows($con);
}

function Hapus($data){
    global $con;

    // menghapus game dari tabel genre
    $deleteGameFromGen = "DELETE FROM genres WHERE game_id ='$data'";
    mysqli_query($con, $deleteGameFromGen);
    
    // menghapus game dari tabel games
    $query = "DELETE FROM games WHERE game_id ='$data'";
    mysqli_query($con, $query);

}

?>