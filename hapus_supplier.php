<?php
// Pastikan ID user tersedia melalui parameter GET
if (isset($_GET['id'])) {
    // Include file functions.php untuk koneksi database
    require 'functions.php';

    // Tangkap ID user dari parameter GET
    $supplier_id = $_GET['id'];

    // Buat query untuk menghapus user berdasarkan ID
    $query = "DELETE FROM supplier WHERE supplier_id = $supplier_id";

    // Lakukan query ke database
    $result = mysqli_query($conn, $query);

    // Periksa apakah query berhasil dijalankan
    if ($result) {
        // Jika berhasil, arahkan kembali ke halaman data_user.php
        header("Location: supplier.php");
        exit; // Hentikan eksekusi script
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal menghapus supplier.";
    }
} else {
    // Jika ID user tidak tersedia melalui parameter GET, tampilkan pesan error
    echo "ID supplier tidak tersedia.";
}
?>
