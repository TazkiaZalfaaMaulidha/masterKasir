<?php
// Include file functions.php untuk mengakses koneksi ke database dan fungsi-fungsinya
require 'functions.php';

// Pastikan ID stok yang akan dihapus tersedia dalam parameter URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lakukan proses penghapusan stok berdasarkan ID yang diterima
    $query = "DELETE FROM produk WHERE produk_id = $id";
    $result = mysqli_query($conn, $query);

    // Jika proses penghapusan berhasil
    if ($result) {
        // Redirect kembali ke halaman stok.php setelah menghapus
        header("Location: stok.php");
        exit(); // Pastikan tidak ada output lain setelah redirect
    } else {
        // Jika terjadi kesalahan dalam proses penghapusan
        echo "Gagal menghapus stok.";
    }
} else {
    // Jika tidak ada ID stok yang diterima dari parameter URL
    echo "ID stok tidak tersedia.";
}
?>
