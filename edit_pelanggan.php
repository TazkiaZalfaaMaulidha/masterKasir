<?php
require 'functions.php';

// Pastikan ada data yang dikirim melalui form
if (isset($_POST['submitEdit'])) {
    // Periksa apakah ID pelanggan tersedia melalui form
    if (isset($_POST['pelanggan_id'])) {
        $pelanggan_id = $_POST['pelanggan_id']; // Ubah $supplier_id menjadi $pelanggan_id

        // Peroleh data yang dikirim melalui form
        $toko_id = $_POST['editToko'];
        $nama_pelanggan = $_POST['editNamaPelanggan']; // Ubah nama input
        $alamat = $_POST['editAlamat'];
        $no_hp = $_POST['editNoHP']; // Ubah nama input

        // Lakukan query untuk mengupdate data pelanggan
        $query = "UPDATE pelanggan SET toko_id = '$toko_id', nama_pelanggan = '$nama_pelanggan', alamat = '$alamat', no_hp = '$no_hp' WHERE pelanggan_id = $pelanggan_id";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo '<script>alert("Data Pelanggan berhasil diupdate");</script>';
            echo '<script>window.location.href = "pelanggan.php";</script>';
        } else {
            echo '<script>alert("Gagal mengupdate data pelanggan");</script>';
        }
    } else {
        echo '<script>alert("ID pelanggan tidak tersedia");</script>';
    }
} else {
    // Jika tidak ada data yang dikirim melalui form, tampilkan pesan kesalahan
    echo '<script>alert("Tidak ada data yang dikirim untuk diupdate");</script>';
}
?>
