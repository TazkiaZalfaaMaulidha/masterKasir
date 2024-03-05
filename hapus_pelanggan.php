<?php
require 'functions.php';

// Pastikan ada parameter id yang dikirim melalui URL
if (isset($_GET['id'])) {
    // Tangkap nilai id pelanggan dari parameter URL
    $pelanggan_id = $_GET['id'];

    // Lakukan query untuk menghapus pelanggan berdasarkan id
    $query = "DELETE FROM pelanggan WHERE pelanggan_id = $pelanggan_id";

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman pelanggan.php
        echo '<script>alert("Data Pelanggan berhasil dihapus");</script>';
        echo '<script>window.location.href = "pelanggan.php";</script>';
    } else {
        // Jika terjadi kesalahan dalam penghapusan, tampilkan pesan kesalahan
        echo '<script>alert("Gagal menghapus data pelanggan");</script>';
    }
} else {
    // Jika parameter id tidak tersedia, tampilkan pesan kesalahan
    echo '<script>alert("ID pelanggan tidak tersedia");</script>';
}
?>
