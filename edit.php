<?php
require 'functions.php';

// Pastikan ada data yang dikirim melalui form
if (isset($_POST['editSubmit'])) {
    // Periksa apakah ID toko tersedia
    if (isset($_GET['id'])) {
        $toko_id = $_GET['id'];

        // Peroleh data yang dikirim melalui form
        $nama_toko = $_POST['editNamaToko'];
        $alamat = $_POST['editAlamat'];
        $no_hp = $_POST['editNoTlpn'];

        // Lakukan query untuk mengupdate data toko
        $query = "UPDATE toko SET nama_toko = '$nama_toko', alamat = '$alamat', no_hp = '$no_hp' WHERE toko_id = $toko_id";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo '<script>alert("Data toko berhasil diupdate");</script>';
            echo '<script>window.location.href = "toko.php";</script>';
        } else {
            echo '<script>alert("Gagal mengupdate data toko");</script>';
        }
    } else {
        echo '<script>alert("ID toko tidak tersedia");</script>';
    }
} else {
    // Jika tidak ada data yang dikirim melalui form, tampilkan pesan kesalahan
    echo '<script>alert("Tidak ada data yang dikirim untuk diupdate");</script>';
}

?>

