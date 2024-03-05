<?php
// Include file koneksi database
require 'functions.php';

// Periksa apakah ada data yang dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah parameter editSubmit terkirim
    if (isset($_POST['editSubmit'])) {
        // Ambil id kategori dari parameter URL
        $kategori_id = $_GET['id'];

        // Ambil data yang dikirim melalui form edit
        $nama_kategori = $_POST['editNamaKategori'];

        // Siapkan query SQL untuk mengupdate data kategori
        $query = "UPDATE produk_kategori SET nama_kategori = '$nama_kategori' WHERE kategori_id = $kategori_id";

        // Eksekusi query untuk mengupdate data kategori
        $result = mysqli_query($conn, $query);

        // Periksa apakah query berhasil dieksekusi
        if ($result) {
            echo '<script>alert("Data kategori berhasil diupdate");</script>';
            echo '<script>window.location.href = "kategori.php";</script>';
        } else {
            echo '<script>alert("Gagal mengupdate data kategori");</script>';
        }
    } else {
        echo '<script>alert("ID kategori tidak tersedia");</script>';
    }
} else {
    // Jika tidak ada data yang dikirim melalui form, tampilkan pesan kesalahan
    echo '<script>alert("Tidak ada data yang dikirim untuk diupdate");</script>';
}
?>
