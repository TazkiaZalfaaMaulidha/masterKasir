<?php
session_start();

if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'functions.php';


if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if ($result) {
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row["password"])) {
                $_SESSION["login"] = true;
                $_SESSION["user_level"] = $row["access_level"];
                $_SESSION["nama_lengkap"] = $row["nama_lengkap"]; // Tambahkan informasi nama lengkap ke dalam session
                $_SESSION["username"] = $row["username"]; // Tambahkan informasi nama lengkap ke dalam session
                $_SESSION['user_id'] = $row['user_id']; // Sesuaikan dengan kolom yang sesuai dengan tabel pengguna

                if ($_SESSION["user_level"] == "admin" || $_SESSION["user_level"] == "kasir") {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<p class='error'>Level akses tidak valid!</p>";
                }
            } else {
                echo "<p class='error'>Username atau password salah!</p>";
            }
        } else {
            echo "<p class='error'>Username atau password salah!</p>";
        }
    } else {
        echo "Query error: " . mysqli_error($conn);
    }
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
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
                width: 317px;
                margin-top: 20px; /* Menggeser form ke atas */
                margin-bottom: center;

            }

            h1 {
                text-align: center;
                color: white;
                margin-bottom: 15px; /* Memberi jarak antara judul dan form */
                margin-top: -10px;
            }

            input[type="text"],
            input[type="password"] {
            width: 100%;
            padding: 10px 0;
            margin-bottom: 10px;
            border: none;
            border-bottom: 1px solid #ccc;
            background-color: transparent; /* Mengatur latar belakang input menjadi transparan */
            box-sizing: border-box;
            outline: none;
            color: white; /* Warna teks */
            }      

            input[type="text"]:focus,
            input[type="password"]:focus {
            border-bottom: 1px solid #3498db;
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

            button:hover {
                background-color: #2980b9;
            }

            .error {
            background-color: #000; /* Warna latar belakang hitam */
            color: #fff; /* Warna teks putih */
            padding: 10px; /* Padding */
            border-radius: 5px; /* Sudut membulat */
            text-align: center; /* Posisi teks */
            position: absolute; /* Menggunakan posisi absolut */
            top: 50px; /* Jarak dari bagian atas */
            left: 50%; /* Posisi horizontal di tengah */
            transform: translateX(-50%); /* Pusatkan horizontal */
            z-index: 999; /* Menempatkan di atas konten */
            width: 300px; /* Lebar pesan kesalahan */
            }
        </style>
    </head>
    <body>
        <div class="container">
            <form action="" method="post">
                <h1>Login</h1>
                <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" required>
                <br>
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>
                <br>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </body>
    </html>
