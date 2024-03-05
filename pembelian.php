<?php
require 'functions.php';

// Mulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    // Jika belum, redirect ke halaman login
    header("Location: login.php");
    exit;
}

// Ambil nilai dari level akses dari session
$level = isset($_SESSION['user_level']) ? $_SESSION['user_level'] : '';

// Untuk mengatur JAM 
date_default_timezone_set('Asia/Jakarta');

// Inisialisasi nilai default untuk form pembelian
$latest_invoice_number = getLatestInvoiceNumber();
if ($latest_invoice_number) {
    $no_faktur_default = incrementInvoiceNumber($latest_invoice_number);
} else {
    $no_faktur_default = "F" . date('Ymd') . "-001"; // Jika belum ada nomor faktur, mulai dari 001
}

$toko_id_default = 1; // Ganti dengan ID toko default Anda

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Retrieve data from form
    $user_id = $_SESSION['user_id']; // User ID kasir (sesuai dengan sesi)
    $toko_id = $_POST['toko_id'];
    $no_faktur = $_POST['no_faktur'];
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $supplier_id = $_POST['supplier_id'];
    $nama_produk = $_POST['nama_produk'];
    $kategori_id = $_POST['kategori_id'];
    $satuan = $_POST['satuan'];
    $stok = $_POST['stok'];
    $total = $_POST['total'];
    $bayar = $_POST['bayar'];
    $sisa = $_POST['sisa'];
    $keterangan = $_POST['keterangan'];

    // TODO: Validasi input form pembelian

    // Simpan data pembelian ke dalam tabel pembelian
    $query_pembelian = "INSERT INTO pembelian (user_id, toko_id, no_faktur, tanggal_pembelian, supplier_id, total, bayar, sisa, keterangan) VALUES ('$user_id', '$toko_id', '$no_faktur', '$tanggal_pembelian', '$supplier_id', '$total', '$bayar', '$sisa', '$keterangan')";

    // Simpan data produk ke dalam tabel produk
    $queryProduk = "INSERT INTO produk (toko_id, nama_produk, kategori_id, satuan, stok) 
    VALUES ('$toko_id', '$nama_produk', '$kategori_id', '$satuan', '$stok')";

    // Eksekusi perintah SQL untuk menyimpan data produk
    $result_produk = mysqli_query($conn, $queryProduk);

    if ($result_produk) {
        echo '<script>alert("Pembelian Produk berhasil");</script>';
    } else {
        echo '<script>alert("Gagal menyimpan data produk");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Pembayaran Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
/* Tambahkan gaya CSS khusus di sini */
.form-wrapper {
    width: 70%; /* Lebar form */
    max-width: 600px; /* Panjang maksimum form */
    min-height: 400px; /* Ketinggian minimum form */
    margin: 0 auto; /* Membuat form berada di tengah */
    margin-top: 50px; /* Jarak atas form */
    padding: 20px; /* Padding form */
    background-color: #2C3333; /* Warna latar belakang form */
    border: 1px solid #2C3333; /* Border form */
    border-radius: 10px; /* Border-radius form */
    box-shadow: -2px -2px 8px rgba(0, 0, 0, 0.3),
                inset 2px 2px 4px rgba(0, 0, 0, 0.1),
                2px 2px 8px rgba(0, 0, 0, 0.3); /* Shadow form */
}

.card-header {
    background-color:  #2C3333; /* Warna latar belakang input */
    color: #fff; /* Warna teks header */
    padding: 10px 15px; /* Padding header */
    border-radius: 10px 10px 0 0; /* Border-radius header */
    border-bottom: 1px solid #fff; /* Garis bawah header */
    text-align: center; /* Membuat judul berada di tengah */
}

.card-header h5.card-title {
    margin-bottom: 0; /* Menghapus margin bawah dari judul */
}

.form-wrapper .card-body {
    background-color: #2C3333; /* Warna latar belakang body */
    color: #fff; /* Warna teks body */
}

.form-wrapper .form-label {
    color: #fff; /* Warna teks label */
}

.form-wrapper .form-control {
    background-color:  #2C3333;/* Warna latar belakang input */
    color: #fff; /* Warna teks input */
    border: none; /* Hapus border input */
    border-radius: 10px; /* Border-radius input */
    box-shadow: inset -2px -2px 8px rgba(0, 0, 0, 0.3),
                inset 2px 2px 4px rgba(0, 0, 0, 0.1),
                inset 2px 2px 8px rgba(0, 0, 0, 0.3); /* Shadow input */
}

.form-wrapper .form-control,
.form-wrapper .form-select {
    background-color:  #2C3333;/* Warna latar belakang input */
    color: #fff; /* Warna teks input */
    border: none; /* Hapus border input */
    border-radius: 10px; /* Border-radius input */
    box-shadow: inset -2px -2px 8px rgba(0, 0, 0, 0.3),
                inset 2px 2px 4px rgba(0, 0, 0, 0.1),
                inset 2px 2px 8px rgba(0, 0, 0, 0.3); /* Shadow input */
                font-family: inherit; /* Mengambil font-family dari parent */
}

/* Tambahkan warna teks placeholder */
.form-wrapper .form-control::placeholder {
    color: #fff; /* Warna teks placeholder */
}

/* Atur posisi tombol dan warna */
.form-wrapper .btn-primary {
    background-color: #2C3333; /* Warna biru untuk tombol */
    color: #fff; /* Warna teks tombol */
    border: none; /* Hapus border tombol */
    border-radius: 25px; /* Border-radius tombol */
    box-shadow: -2px -2px 8px rgba(0, 0, 0, 0.3),
                inset 2px 2px 4px rgba(0, 0, 0, 0.1),
                2px 2px 8px rgba(0, 0, 0, 0.3); /* Shadow tombol */
    outline: none; /* Hapus outline tombol */
    cursor: pointer; /* Pointer saat hover */
    float: right; /* Posisi tombol ke kanan */
}

.form-wrapper .btn-primary:hover {
    transform: scale(0.98); /* Efek scaling saat hover */
    box-shadow: inset -2px -2px 8px rgba(0, 0, 0, 0.3),
                inset 2px 2px 4px rgba(0, 0, 0, 0.1),
                inset 2px 2px 8px rgba(0, 0, 0, 0.3); /* Shadow tombol saat hover */
}

</style>

</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="#">Aplikasi Kasir</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Interface</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class='fas fa-folder-open'></i></div>
                            Data
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="pelanggan.php">Data Pelanggan</a>
                                <?php if ($level == 'admin') { ?>
                                    <a class="nav-link" href="supplier.php">Data Supplier</a>
                                    <a class="nav-link" href="data_user.php">Data User</a>
                                <?php } ?>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class='fas fa-money-check-alt'></i></div>
                            Transaksi
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                    Kelola Data
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="penjualan.php">Penjualan</a>
                                        <a class="nav-link" href="pembelian.php">Pembelian</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>                       
                        <a class="nav-link" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
                <!-- Footer Sidebar -->
                <div class="sb-sidenav-footer">
                    <!-- Informasi Pengguna -->
                    <div class="small">Logged in as:</div>
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo $_SESSION['username'];
                    } else {
                        echo "Username tidak tersedia"; // Tambahkan pesan default jika username tidak tersedia
                    }
                    ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <!-- Form Pembelian -->
                    <div class="form-wrapper">
    <form method="post" action="">                        
        <div class="card-header">
            <h5 class="card-title">Data Pembelian</h5>
        </div>                                  
                                    <div class="mb-3">
                                        <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                                        <input type="text" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly placeholder="Tanggal Pembelian">
                                    </div>
                                    <div class="mb-3">
                                        <label for="supplier_id" class="form-label">Nama Supplier</label>
                                        <select class="form-select" id="supplier_id" name="supplier_id">
                                            <?php
                                            // Query untuk mengambil data toko
                                            $query_supplier = "SELECT * FROM supplier";
                                            $result_supplier = mysqli_query($conn, $query_supplier);
                                            while ($row_supplier = mysqli_fetch_assoc($result_supplier)) {
                                                echo "<option value='" . $row_supplier['supplier_id'] . "'>";
                                                // Menampilkan nama toko dalam opsi dropdown
                                                echo $row_supplier['nama_supplier'];
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="toko_id" class="form-label">Toko</label>
                                        <select class="form-select" id="toko_id" name="toko_id">
                                            <?php
                                            // Query untuk mengambil data toko
                                            $query_toko = "SELECT * FROM toko";
                                            $result_toko = mysqli_query($conn, $query_toko);
                                            while ($row_toko = mysqli_fetch_assoc($result_toko)) {
                                                echo "<option value='" . $row_toko['toko_id'] . "'>";
                                                // Menampilkan nama toko dalam opsi dropdown
                                                echo $row_toko['nama_toko'];
                                                echo "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_faktur" class="form-label">No.Faktur</label>
                                        <input type="text" class="form-control" id="no_faktur" name="no_faktur" value="<?php echo $no_faktur_default; ?>" placeholder="Nomor Faktur">
                                    </div>
                                    <div class="mb-3">
    <label for="kategori_id" class="form-label">Pilih Kategori</label>
    <select class="form-select" id="kategori_id" name="kategori_id">
        <?php
        // Query untuk mengambil data kategori produk
        $query_kategori = "SELECT * FROM produk_kategori";
        $result_kategori = mysqli_query($conn, $query_kategori);
        while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
            echo "<option value='" . $row_kategori['kategori_id'] . "'>";
            // Menampilkan nama kategori dalam opsi dropdown
            echo $row_kategori['nama_kategori'];
            echo "</option>";
        }
        ?>
    </select>
</div>

                                    <div class="mb-3">
                                        <label for="nama_produk" class="form-label"></label>
                                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Nama Produk" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="satuan" class="form-label"></label>
                                        <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Satuan" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="stok" class="form-label"></label>
                                        <input type="text" class="form-control" id="stok" name="stok" placeholder="Stok" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="total" class="form-label"></label>
                                        <input type="text" class="form-control" id="total" name="total" placeholder="Total" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bayar" class="form-label"></label>
                                        <input type="text" class="form-control" id="bayar" name="bayar" placeholder="Bayar" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sisa" class="form-label"></label>
                                        <input type="text" class="form-control" id="sisa" name="sisa" placeholder="Sisa" required>
                                    </div>
                                    <div class="mb-3">
    <label for="keterangan" class="form-label"></label>
    <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" required></textarea>
</div>
<div class="mb-3">
    <button type="submit" class="btn btn-primary btn-block" name="submit">Bayar</button>
</div>


                            </div>
                        </form>
                         
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Tazkia Zalfaa Maulidha 2024</div>
                    </div>
                </div>
            </footer>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-dark text-white">
                    Apakah Anda yakin ingin logout?
                </div>
                <div class="modal-footer bg-dark text-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
    </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
            <script src="js/scripts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
            <script src="assets/demo/chart-area-demo.js"></script>
            <script src="assets/demo/chart-bar-demo.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
            <script src="js/datatables-simple-demo.js"></script>
            <script>
                // Fungsi untuk menampilkan konfirmasi penghapusan
                function confirmDelete(produk_id) {
                    if (confirm("Apakah Anda yakin ingin menghapus item ini dari keranjang?")) {
                        // Jika pengguna menekan OK, kirimkan form untuk penghapusan
                        document.getElementById('hapus_form_' + produk_id).submit();
                    }
                }
            </script>
            <script>
                // Fungsi untuk menghitung sisa bayar saat input jumlah pembayaran berubah
                document.getElementById('bayar').addEventListener('input', function() {
                    var total = parseFloat(document.getElementById('total').value);
                    var bayar = parseFloat(this.value);
                    var sisa = bayar - total;
                    document.getElementById('sisa').value = sisa.toFixed(2); // Menampilkan sisa dengan dua angka di belakang koma
                });
            </script>
</body>

</html>
