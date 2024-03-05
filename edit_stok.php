<?php
require 'functions.php';

if (isset($_POST['submitEdit'])) {
    $produk_id = $_POST['produk_id'];
    $toko_id = $_POST['editToko'];
    $nama_produk = $_POST['editNamaProduk'];
    $kategori_id = $_POST['editKategoriProduk'];
    $satuan = $_POST['editSatuan'];
    $harga_beli = $_POST['editHargaBeli'];
    $harga_jual = $_POST['editHargaJual'];    

    // Query untuk mengupdate data produk
    $query = "UPDATE produk SET 
                toko_id = '$toko_id', 
                nama_produk = '$nama_produk', 
                kategori_id = '$kategori_id', 
                satuan = '$satuan', 
                harga_beli = '$harga_beli', 
                harga_jual = '$harga_jual'             
              WHERE produk_id = '$produk_id'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo '<script>alert("Data produk berhasil diperbarui");</script>';
        echo '<script>window.location.href = "stok.php";</script>';
    } else {
        echo '<script>alert("Gagal memperbarui data produk");</script>';
    }
}
?>
