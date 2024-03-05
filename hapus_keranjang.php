<?php
session_start();

// Periksa apakah data produk yang akan dihapus telah dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hapus_dari_keranjang"])) {
    $produk_id_to_delete = $_POST["hapus_dari_keranjang"];

    // Periksa apakah keranjang belanja tidak kosong
    if (!empty($_SESSION['keranjang'])) {
        // Cari posisi item dalam keranjang
        $index_to_delete = -1;
        foreach ($_SESSION['keranjang'] as $index => $item) {
            if ($item['produk_id'] == $produk_id_to_delete) {
                $index_to_delete = $index;
                break;
            }
        }

        // Jika item ditemukan, hapus dari keranjang
        if ($index_to_delete !== -1) {
            unset($_SESSION['keranjang'][$index_to_delete]);
        }
    }
}

// Redirect kembali ke halaman sebelumnya
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>
