<?php
// Pastikan ID user tersedia melalui parameter GET
if (isset($_GET['id'])) {
    // Include file functions.php untuk koneksi database
    require 'functions.php';

    // Tangkap ID user dari parameter GET
    $user_id = $_GET['id'];

    // Buat query untuk menghapus user berdasarkan ID
    $query = "DELETE FROM users WHERE user_id = $user_id";

    // Lakukan query ke database
    $result = mysqli_query($conn, $query);

    // Periksa apakah query berhasil dijalankan
    if ($result) {
        // Jika berhasil, arahkan kembali ke halaman data_user.php
        header("Location: data_user.php");
        exit; // Hentikan eksekusi script
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal menghapus user.";
    }
} else {
    // Jika ID user tidak tersedia melalui parameter GET, tampilkan pesan error
    echo "ID user tidak tersedia.";
}
?>
