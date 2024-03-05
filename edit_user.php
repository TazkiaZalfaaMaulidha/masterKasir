<?php
require 'functions.php';

// Pastikan ada data yang dikirim melalui form
if (isset($_POST['editSubmit'])) {
    // Periksa apakah ID user tersedia melalui URL
    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];

        // Peroleh data yang dikirim melalui form
        $nama_lengkap = $_POST['editNamaLengkap'];
        $alamat = $_POST['editAlamat'];
        $email = $_POST['editEmail'];

        // Lakukan query untuk mengupdate data user
        $query = "UPDATE users SET nama_lengkap = '$nama_lengkap', alamat = '$alamat', email = '$email' WHERE user_id = $user_id";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo '<script>alert("Data user berhasil diupdate");</script>';
            echo '<script>window.location.href = "data_user.php";</script>';
        } else {
            echo '<script>alert("Gagal mengupdate data user");</script>';
        }
    } else {
        echo '<script>alert("ID user tidak tersedia");</script>';
    }
} else {
    // Jika tidak ada data yang dikirim melalui form, tampilkan pesan kesalahan
    echo '<script>alert("Tidak ada data yang dikirim untuk diupdate");</script>';
}
?>
