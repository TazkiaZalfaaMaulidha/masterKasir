<?php
require 'functions.php';

// Mulai sesi
session_start();

// Pastikan koneksi database berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Periksa apakah variabel $_POST['produk_id'] sudah diset
if (isset($_POST['produk_id'])) {
    $produkId = $_POST['produk_id'];

    // Query untuk mendapatkan harga jual produk berdasarkan ID produk
    $query = "SELECT harga_jual FROM produk WHERE produk_id = '$produkId'";
    $result = mysqli_query($conn, $query);

    // Periksa apakah query berhasil dieksekusi
    if ($result) {
        // Periksa apakah hasil query mengembalikan baris data
        if (mysqli_num_rows($result) > 0) {
            // Ambil baris pertama dari hasil query
            $row = mysqli_fetch_assoc($result);
            // Ambil nilai harga jual dari baris yang dipilih
            $hargaJual = $row['harga_jual'];
            // Kembalikan harga jual sebagai respons
            echo $hargaJual;
        } else {
            echo "Data produk tidak ditemukan";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Produk ID tidak diterima";
}
?>
