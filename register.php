<?php

require 'functions.php';

if( isset($_POST["register"])){
 
    if( register($_POST) > 0 ) {

        echo "<script>
               alert('Register Berhasil!');
              </script>";
    } else {
        echo mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /*background-image: url('img/marvel.jpeg'); /*Ganti dengan path gambar background yang sesuai */
            background-color: black;
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            /*background-color: rgba(255, 255, 255, 0.8); /* Transparan */
            background-image: url(img/bg4.jpeg);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 17px #0ef;
            width: 350px;
            margin-top: 20px; /* Menggeser form ke atas */

        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 20px; /* Memberi jarak antara judul dan form */
        }

        input[type="text"],
        input[type="password"] {
            width: 100%; /* Mengisi lebar kontainer */
            padding: 10px 0; /* Padding atas dan bawah */
            margin-bottom: 10px;
            border: none; /* Menghilangkan border bawaan */
            border-bottom: 1px solid #ccc; /* Menampilkan garis bawah */
            background-color: transparent; /* Menghilangkan background warna */
            box-sizing: border-box; /* Ukuran box termasuk padding dan border */
            outline: none; /* Menghilangkan focus border */
            color: white;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
        border-bottom: 1px solid #3498db; /* Garis bawah saat input difokuskan */
        color: white; /* Warna teks saat input difokuskan */
       }

        button[type="submit"] {
            background-color: #3498db; /* Warna biru */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #2980b9; /* Warna biru ketika dihover */
        }

       /* Gaya untuk tautan login */
       p {
        color: #ccc; /* Atur warna teks sesuai kebutuhan */
        text-align: center;
       }

        a {
            color: white; /* Warna teks tautan */
            text-decoration: none; /* Menghilangkan dekorasi tautan */
        }

        a:hover {
            text-decoration: underline; /* Tampilkan garis bawah saat dihover */
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <h1>Registrasi</h1>
           
            <input type="text" name="email" id="email" placeholder="Email" autocomplete="off" required>

            <input type="text" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off" required>

            <input type="text" name="alamat" id="alamat" placeholder="Alamat" autocomplete="" required>

            <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" required>

            <input type="text" name="access_level" id="access_level" placeholder="Access Level" autocomplete="off" aurequired>

            <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>

            <input type="password" name="password2" id="password2" placeholder="Konfirmasi Password" autocomplete="off" required>

            <button type="submit" name="register">Register</button>
        </form>
        <p>Batal Menambahkan? <a href="index.php">Kembali</a></p>
    </div>
</body>
</html>
