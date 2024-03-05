<?php
// Pastikan koneksi ke database sudah dilakukan sebelum file ini di-include
require 'functions.php';

// Periksa apakah ID kategori disertakan dalam parameter URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Escape input untuk menghindari serangan SQL Injection
    $kategori_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Buat query SQL untuk menghapus kategori
    $query = "DELETE FROM produk_kategori WHERE kategori_id = $kategori_id";

    // Lakukan query ke database
    if (mysqli_query($conn, $query)) {
        // Jika penghapusan berhasil, redirect kembali ke halaman kategori dengan pesan sukses
        header("Location: kategori.php?pesan=hapus_sukses");
        exit;
    } else {
        // Jika terjadi kesalahan saat menghapus kategori, redirect kembali ke halaman kategori dengan pesan gagal
        header("Location: kategori.php?pesan=hapus_gagal");
        exit;
    }
} else {
    // Jika ID kategori tidak disertakan atau tidak valid, redirect kembali ke halaman kategori
    header("Location: kategori.php");
    exit;
}
?>
