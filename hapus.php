<?php
require 'functions.php';

// Periksa apakah ID toko telah diterima melalui parameter URL
if (isset($_GET['id'])) {
    $toko_id = $_GET['id'];

    // Lakukan query untuk menghapus toko dengan ID yang sesuai
    $query = "DELETE FROM toko WHERE toko_id = $toko_id";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo '<script>alert("Data toko berhasil dihapus");</script>';
        echo '<script>window.location.href = "toko.php";</script>';
    } else {
        echo '<script>alert("Gagal menghapus data toko");</script>';
    }
} else {
    echo '<script>alert("ID toko tidak diterima");</script>';
}

?>
