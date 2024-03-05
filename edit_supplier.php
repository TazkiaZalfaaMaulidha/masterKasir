<?php
require 'functions.php';

// Pastikan ada data yang dikirim melalui form
if (isset($_POST['submitEdit'])) {
    // Periksa apakah ID supplier tersedia melalui form
    if (isset($_POST['supplier_id'])) {
        $supplier_id = $_POST['supplier_id'];

        // Peroleh data yang dikirim melalui form
        $toko_id = $_POST['editToko'];
        $kategori_id = $_POST['kategori_id'];
        $nama_supplier = $_POST['editNamaSupplier'];
        $no_hp = $_POST['editNoHp'];
        $alamat = $_POST['editAlamatSupplier'];

        // Lakukan query untuk mengupdate data supplier
        $query = "UPDATE supplier SET toko_id = '$toko_id', kategori_id = '$kategori_id', nama_supplier = '$nama_supplier', no_hp = '$no_hp', alamat = '$alamat' WHERE supplier_id = $supplier_id";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo '<script>alert("Data supplier berhasil diupdate");</script>';
            echo '<script>window.location.href = "supplier.php";</script>';
        } else {
            echo '<script>alert("Gagal mengupdate data supplier");</script>';
        }
    } else {
        echo '<script>alert("ID supplier tidak tersedia");</script>';
    }
} else {
    // Jika tidak ada data yang dikirim melalui form, tampilkan pesan kesalahan
    echo '<script>alert("Tidak ada data yang dikirim untuk diupdate");</script>';
}
?>
