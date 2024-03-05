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

// Periksa koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Untuk mengatur JAM 
date_default_timezone_set('Asia/Jakarta');

// Memulai session

// Inisialisasi session keranjang jika belum ada
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Inisialisasi variabel $produk
$produk = [];

// Proses pencarian produk jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["keyword"])) {
    $keyword = $_POST["keyword"];
    // Query untuk mencari produk berdasarkan keyword
    $query = "SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%'";
    $result = mysqli_query($conn, $query);
    // Lakukan sesuatu dengan hasil pencarian
    $produk = cari_produk($keyword);
}

// Inisialisasi Total Harga
$total_harga = 0;

// Perhitungan Total Harga dan Jumlah Item di Keranjang
foreach ($_SESSION['keranjang'] as $item) {
    // Ambil harga jual dari item saat ini
    $harga_jual = floatval($item['harga_jual']);

    // Akumulasi total harga dengan harga jual dari setiap item
    $total_harga += $harga_jual;
}

// Perbaiki Perhitungan Total Harga jika tombol tambah ke keranjang ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tambah_ke_keranjang"])) {
    $produk_id = $_POST["produk_id"];
    $nama_produk = $_POST["nama_produk"];   
    $harga_jual = $_POST["harga_jual"];

    // Tambahkan produk ke session keranjang
    $_SESSION['keranjang'][] = [
        'produk_id' => $produk_id,
        'nama_produk' => $nama_produk,       
        'harga_jual' => $harga_jual
    ];

    // Perbarui jumlah item dan total harga
    $jumlah_item = count($_SESSION['keranjang']);

    // Tambahkan harga jual baru ke total harga
    $total_harga += floatval($harga_jual);
}

// Memproses pembayaran jika tombol Bayar ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Mendapatkan data pembayaran dari form
    $tanggal_penjualan = $_POST["tanggal_penjualan"];
    $user_id = $_POST["user_id"];
    $namaPelanggan = $_POST["nama_pelanggan"];   
    $alamat = $_POST["alamat"];
    $noHp = $_POST["no_hp"];    
    $keterangan = $_POST["keterangan"];
    $jumlah_item = $_POST['jumlah_item'];
    $total = $_POST["total"];
    $bayar = $_POST["bayar"];
    $sisa = $_POST["sisa"];

    // Memulai transaksi
    mysqli_begin_transaction($conn);

    try {
        // Simpan data pelanggan ke dalam tabel pelanggan
        $queryPelanggan = "INSERT INTO pelanggan ( nama_pelanggan, alamat, no_hp) 
                           VALUES ('$namaPelanggan', '$alamat', '$noHp')";

        $resultPelanggan = mysqli_query($conn, $queryPelanggan);
        if (!$resultPelanggan) {
            throw new Exception("Query pelanggan gagal dieksekusi: " . mysqli_error($conn));
        }

        // Simpan data transaksi ke dalam tabel penjualan
        $tanggalTransaksi = date('Y-m-d H:i:s');
        $queryPenjualan = "INSERT INTO penjualan ( user_id, tanggal_penjualan, pelanggan_id, total, bayar, sisa, keterangan) 
                           VALUES ('$user_id', '$tanggal_penjualan', LAST_INSERT_ID(), '$total', '$bayar', '$sisa', '$keterangan')";

        $resultPenjualan = mysqli_query($conn, $queryPenjualan);
        if (!$resultPenjualan) {
            throw new Exception("Query penjualan gagal dieksekusi: " . mysqli_error($conn));
        }

        // Simpan data detail penjualan ke dalam tabel penjualan_detail
        $penjualanId = mysqli_insert_id($conn);
        foreach ($_SESSION['keranjang'] as $item) {
            $produk_id = $item['produk_id'];
            $qty = 1;
            $harga_jual = $item['harga_jual'];

            $queryDetailPenjualan = "INSERT INTO penjualan_detail (penjualan_id, produk_id, qty, harga_jual) 
            VALUES ('$penjualanId', '$produk_id', '$qty', '$harga_jual')";

            $resultDetailPenjualan = mysqli_query($conn, $queryDetailPenjualan);
            if (!$resultDetailPenjualan) {
                throw new Exception("Query detail penjualan gagal dieksekusi: " . mysqli_error($conn));
            }

            // Kurangi stok barang di database sesuai dengan jumlah terjual
            $queryKurangiStok = "UPDATE produk SET stok = stok - $qty WHERE produk_id = '$produk_id'";
            $resultKurangiStok = mysqli_query($conn, $queryKurangiStok);
            if (!$resultKurangiStok) {
                throw new Exception("Query kurangi stok gagal dieksekusi: " . mysqli_error($conn));
            }
        }

        // Commit transaksi
        mysqli_commit($conn);

        // Notifikasi pembayaran sukses
        echo '<script>alert("Pembayaran sukses! Transaksi telah berhasil disimpan.");</script>';
        // Refresh halaman untuk memperbarui tabel
        echo '<script>window.location.href = "penjualan.php";</script>';
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($conn);
        echo '<script>alert("Pembayaran Gagal ! ' . $e->getMessage() . '");</script>';
    }

    // Setelah menyimpan transaksi, kosongkan keranjang belanja
    $_SESSION['keranjang'] = [];
}

// Ambil ID pengguna dari sesi
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Query untuk mengambil nama kasir dari database berdasarkan ID pengguna
$query_kasir = "SELECT nama_lengkap FROM users WHERE user_id = $user_id";
$result_kasir = mysqli_query($conn, $query_kasir);

// Periksa apakah query berhasil dieksekusi dan hasilnya tidak kosong
if ($result_kasir && mysqli_num_rows($result_kasir) > 0) {
    // Ambil nama kasir dari hasil query
    $row_kasir = mysqli_fetch_assoc($result_kasir);
    $nama_kasir = isset($row_kasir['nama_lengkap']) ? $row_kasir['nama_lengkap'] : "Nama Kasir Tidak Ditemukan";
} else {
    $nama_kasir = "Nama Kasir Tidak Ditemukan";
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
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
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

            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <!-- Search -->
<div class="row mt-3">
    <div class="col-sm-4">
        <div class="card card-primary mb-3">
            <div class="card-header bg-dark text-white">
                <h5><i class="fa fa-search"></i> Cari Barang</h5>
            </div>
            <div class="card-body">
            <form action="" method="post" id="">
    <input type="text" id="cari" class="form-control" name="keyword" placeholder="Masukkan: Kode / Nama Barang [ENTER]">
</form>
            </div>
        </div>
    </div>
    <!-- Hasil cari -->
<div class="col-sm-8">
    <div class="card card-primary mb-3">
        <div class="card-header bg-dark text-white">
            <h5><i class="fa fa-list"></i> Hasil Pencarian</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>Nama Produk</th>                          
                            <th>Harga</th>                           
                            <th>Action</th>
                            <!-- Tambahkan kolom lain jika diperlukan -->
                        </tr>
                    </thead>
                    <tbody>
    <?php foreach ($produk as $row) : ?>
        <tr>
            <td><?php echo $row['nama_produk']; ?></td>           
            <td>RP. <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></td>
            <td>
                <!-- Form untuk menambahkan produk ke keranjang -->
                <form action="" method="post">
                    <input type="hidden" name="produk_id" value="<?php echo $row['produk_id']; ?>">
                    <input type="hidden" name="nama_produk" value="<?php echo $row['nama_produk']; ?>">                 
                    <input type="hidden" name="harga_jual" value="<?php echo $row['harga_jual']; ?>">
                    <button type="submit" class="btn btn-primary" name="tambah_ke_keranjang">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Keranjang / Untuk barang yang di beli -->
<div class="col-sm-12">
    <div class="card card-primary">
        <div class="card-header bg-dark text-white">
            <h5><i class="fa fa-shopping-cart"></i> KASIR</h5>
        </div>
        <div class="card-body">
            <div id="keranjang" class="table-responsive">
                <table class="table table-bordered w-100" id="tabelKeranjang">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>Nama Barang</th>                            
                            <th> Harga
                            <?php
// Hitung total harga produk di keranjang
$total_harga = 0;
foreach ($_SESSION['keranjang'] as $item) {
    // Konversi nilai harga jual menjadi bilangan pecahan
    $harga_jual = floatval($item['harga_jual']);
    $total_harga += $harga_jual;
}

?>
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['keranjang'] as $item) : ?>
                            <tr>
    <td><?php echo $item['nama_produk']; ?></td>
    <td>RP. <?php echo number_format(floatval($item['harga_jual']), 0, ',', '.'); ?></td>
    <td>
<!-- Tombol untuk menghapus item dari keranjang -->
<a href="#" onclick="confirmDelete(<?php echo $item['produk_id']; ?>)"><i class="fas fa-trash"></i></a>
<!-- Form untuk penghapusan item dari keranjang -->
<form id="hapus_form_<?php echo $item['produk_id']; ?>" action="hapus_keranjang.php" method="post" style="display: none;">
    <input type="hidden" name="hapus_dari_keranjang" value="<?php echo $item['produk_id']; ?>">
</form>
    </td>
</tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Tombol untuk membuka modal pembayaran -->
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPembayaran">
                        Bayar
                    </button>                    
                </div>
            </div>
        </div>
    </div>
</div>

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
<!-- Modal Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1" aria-labelledby="modalPembayaranLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalPembayaranLabel">Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>               
            <div class="modal-body bg-dark text-white">
                <form method="post" action="">
                  <div class="mb-3">
    <label for="kasir" class="form-label">Kasir</label>
    <input type="text" class="form-control" id="kasir" name="kasir" value="<?php echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : ''; ?>" readonly>
    <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
</div>
                <div class="mb-3">  
    <label for="tanggal_penjualan" class="form-label">Tanggal Penjualan</label>
    <input type="text" class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
    </div> 
        <div class="mb-3">
<div class="mb-3">
<label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                        </div>
<!-- Memperbarui dropdown produk di dalam modal pembayaran -->
<div class="mb-3">
    <label for="produk_id" class="form-label">Produk</label>
    <select name="produk_id" class="form-control mb-3" id="produk_id">
        <?php foreach ($_SESSION['keranjang'] as $item) : ?>
            <option value="<?= $item['produk_id']; ?>">
                <?= $item['nama_produk']; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
<label for="jumlah_item" class="form-label">Jumlah Item</label>
<input type="number" name="jumlah_item" placeholder="Jumlah Item" class="form-control mb-3" id="jumlah_item">
</div>
<div class="mb-3">
    <label for="total" class="form-label">Total</label>
    <input name="total" type="text" class="form-control" id="total" value="<?php echo $total_harga; ?>" readonly>
</div>

                    <div class="mb-3">
                        <label for="bayar" class="form-label">Bayar</label>
                        <input type="text" class="form-control" id="bayar" name="bayar" required>
                    </div>
                    <div class="mb-3">
                        <label for="sisa" class="form-label">Sisa</label>
                        <input type="text" class="form-control" id="sisa" name="sisa" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" rows="3" name="keterangan" required></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" name="submit">Bayar</button>
                
                </form>
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
<!-- Pastikan jQuery dimuat sebelum skrip Anda -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Pastikan skrip ini hanya dijalankan setelah jQuery dimuat
    $(document).ready(function() {
        function hitungTotal() {
            var produkId = $('#produk_id').val();
            var jumlahItem = $('#jumlah_item').val();

            // Mengambil harga jual produk dari database
            $.ajax({
                url: 'getHargaProduk.php',
                type: 'post',
                data: {
                    produk_id: produkId
                },
                success: function(hargaJual) {
                    // Mengonversi hargaJual menjadi bilangan pecahan
                    hargaJual = parseFloat(hargaJual);

                    // Menghitung total harga berdasarkan jumlah item dan harga jual
                    var totalHarga = jumlahItem * hargaJual;

                    // Menetapkan nilai total ke input total
                    $('#total').val(totalHarga);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Tambahkan penanganan kesalahan di sini, jika diperlukan
                }
            });
        }

        // Event listener ketika produk atau jumlah item berubah
        $('#produk_id, #jumlah_item').change(hitungTotal);
    });
</script>

</body>
</html>
