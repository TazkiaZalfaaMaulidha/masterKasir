
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

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {
    // Panggil fungsi addProduk() untuk menyimpan data
    $data = [
        'toko_id' => $_POST['toko_id'],
        'nama_produk' => $_POST['nama_produk'],
        'kategori_id' => $_POST['kategori_id'],
        'kode_produk' => $_POST['kode_produk'],
        'satuan' => $_POST['satuan'],
        'harga_beli' => $_POST['harga_beli'],
        'harga_jual' => $_POST['harga_jual']
    ];

    // Panggil fungsi addProduk() untuk menyimpan data ke dalam tabel produk
    $result = addProduk($data);

    if ($result) {
        echo '<script>alert("Data produk berhasil disimpan");</script>';
        // Refresh halaman untuk memperbarui tabel
        echo '<script>window.location.href = "stok.php";</script>';
    } else {
        echo '<script>alert("Gagal menyimpan data produk");</script>';
    }
}

// Query untuk mengambil data penjualan dari tabel penjualan
$query_penjualan = "SELECT penjualan.*, users.nama_lengkap, produk.nama_produk, pelanggan.nama_pelanggan 
                    FROM penjualan 
                    LEFT JOIN users ON penjualan.user_id = users.user_id
                    LEFT JOIN penjualan_detail ON penjualan.penjualan_id = penjualan_detail.penjualan_id
                    LEFT JOIN produk ON penjualan_detail.produk_id = produk.produk_id
                    LEFT JOIN pelanggan ON penjualan.pelanggan_id = pelanggan.pelanggan_id";
$result_penjualan = mysqli_query($conn, $query_penjualan);

// Inisialisasi variabel $penjualan sebagai array kosong
$penjualan = [];

// Periksa apakah query berhasil dieksekusi
if ($result_penjualan) {
    // Ambil hasil query dan simpan ke dalam array $penjualan
    while ($row_penjualan = mysqli_fetch_assoc($result_penjualan)) {
        $penjualan[] = $row_penjualan;
    }
}

// Query untuk mengambil data produk masuk dari tabel
$query = "SELECT * FROM produk";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Pembayaran Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" crossorigin="anonymous">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
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
                                <a class="nav-link" href="order.php">Data Order</a>
                                <a class="nav-link" href="supplier.php">Data Supplier</a>
                                <a class="nav-link" href="data_user.php">Data User</a>
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
                    <!-- Table for Data Produk -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h2>Data Produk Masuk</h2>
                            <table class="table table-striped table-bordered">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th scope="col">Nama Toko</th>
                                        <th scope="col">Nama Produk</th>
                                        <th scope="col">Kategori</th>                                     
                                        <th scope="col">Satuan</th>
                                        <th scope="col">Stok</th>
                                        <th scope="col">Harga Beli</th>
                                        <th scope="col">Harga Jual</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                        <tr>
                                            <?php
                                            // Ambil nama toko dari tabel toko berdasarkan toko_id
                                            $toko_id = $row['toko_id'];
                                            $nama_toko = '';
                                            $query_toko = "SELECT nama_toko FROM toko WHERE toko_id = '$toko_id'";
                                            $result_toko = mysqli_query($conn, $query_toko);
                                            if ($result_toko && mysqli_num_rows($result_toko) > 0) {
                                                $row_toko = mysqli_fetch_assoc($result_toko);
                                                $nama_toko = $row_toko['nama_toko'];
                                            }

                                            // Ambil nama kategori dari tabel produk_kategori berdasarkan kategori_id
                                            $kategori_id = $row['kategori_id'];
                                            $nama_kategori = '';
                                            $query_kategori = "SELECT nama_kategori FROM produk_kategori WHERE kategori_id = '$kategori_id'";
                                            $result_kategori = mysqli_query($conn, $query_kategori);
                                            if ($result_kategori && mysqli_num_rows($result_kategori) > 0) {
                                                $row_kategori = mysqli_fetch_assoc($result_kategori);
                                                $nama_kategori = $row_kategori['nama_kategori'];
                                            }
                                            ?>
                                            <td><?php echo $nama_toko; ?></td>
                                            <td><?php echo $row['nama_produk']; ?></td>
                                            <td><?php echo $nama_kategori; ?></td>                                          
                                            <td><?php echo $row['satuan']; ?></td>
                                            <td><?php echo $row['stok']; ?></td>
                                            <td><?php echo $row['harga_beli']; ?></td>
                                            <td><?php echo $row['harga_jual']; ?></td>
                                            <td>
                                                <!-- Icon edit -->
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $row['produk_id']; ?>"><i class="fas fa-edit"></i></a>
                                                <!-- Icon hapus -->
                                                <a href="#" onclick="confirmDelete(<?php echo $row['produk_id']; ?>)"><i class="fas fa-trash"></i></a>
                                            </td>

                                            <!-- Modal for Edit Product -->
                                            <div class="modal fade" id="editProductModal<?php echo $row['produk_id']; ?>" tabindex="-1" aria-labelledby="editProductModalLabel<?php echo $row['produk_id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editProductModalLabel<?php echo $row['produk_id']; ?>">
                                                                Edit Product</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Form for editing product -->
                                                            <form action="edit_stok.php" method="post">
                                                                <div class="mb-3">
                                                                    <label for="editToko" class="form-label">Nama
                                                                        Toko</label>
                                                                    <select class="form-control" id="editToko" name="editToko">
                                                                        <?php
                                                                        // Query untuk mengambil data toko
                                                                        $query_toko = "SELECT * FROM toko";
                                                                        $result_toko = mysqli_query($conn, $query_toko);
                                                                        while ($row_toko = mysqli_fetch_assoc($result_toko)) {
                                                                            echo "<option value='" . $row_toko['toko_id'] . "'";
                                                                            // Jika toko sama dengan yang sedang diedit, tandai sebagai terpilih
                                                                            if ($row_toko['toko_id'] == $toko_id) {
                                                                                echo " selected";
                                                                            }
                                                                            echo ">";
                                                                            // Menampilkan nama toko dalam opsi dropdown
                                                                            echo $row_toko['nama_toko'];
                                                                            echo "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="editNamaProduk" class="form-label">Nama
                                                                        Produk</label>
                                                                    <input type="text" class="form-control" id="editNamaProduk" name="editNamaProduk" value="<?php echo $row['nama_produk']; ?>">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="editKategoriProduk" class="form-label">Kategori Produk</label>
                                                                    <select class="form-control" id="editKategoriProduk" name="editKategoriProduk">
                                                                        <?php
                                                                        // Query untuk mengambil data kategori produk
                                                                        $query_kategori = "SELECT * FROM produk_kategori";
                                                                        $result_kategori = mysqli_query($conn, $query_kategori);
                                                                        while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
                                                                            echo "<option value='" . $row_kategori['kategori_id'] . "'";
                                                                            // Jika kategori produk sama dengan yang sedang diedit, tandai sebagai terpilih
                                                                            if ($row_kategori['kategori_id'] == $row['kategori_id']) {
                                                                                echo " selected";
                                                                            }
                                                                            echo ">";
                                                                            // Menampilkan nama kategori dalam opsi dropdown
                                                                            echo $row_kategori['nama_kategori'];
                                                                            echo "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>    
                                                                <div class="mb-3">
                                                                    <label for="editSatuan" class="form-label">Satuan</label>
                                                                    <input type="text" class="form-control" id="editSatuan" name="editSatuan" value="<?php echo $row['satuan']; ?>">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="editHargaBeli" class="form-label">Harga
                                                                        Beli</label>
                                                                    <input type="text" class="form-control" id="editHargaBeli" name="editHargaBeli" value="<?php echo $row['harga_beli']; ?>">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="editHargaJual" class="form-label">Harga
                                                                        Jual</label>
                                                                    <input type="text" class="form-control" id="editHargaJual" name="editHargaJual" value="<?php echo $row['harga_jual']; ?>">
                                                                </div>
                                                                <input type="hidden" name="produk_id" value="<?php echo $row['produk_id']; ?>">
                                                                <!-- Pastikan nama tombol submit adalah "submitEdit" -->
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary" name="submitEdit">Edit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="container-fluid px-4">
                    <!-- Table for Data Produk Keluar -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h2>Data Produk Keluar</h2>
                            <table class="table table-striped table-bordered">
                            <thead class="bg-dark text-white">
    <tr>
        <th scope="col">Tanggal Penjualan</th>
        <th scope="col">Kasir</th>                                       
        <th scope="col">Nama Produk</th>                                        
        <th scope="col">Nama Pelanggan</th>                                       
        <th scope="col">Total</th>
        <th scope="col">Keterangan</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($penjualan as $row) : ?>
        <tr>
            <td><?= isset($row['tanggal_penjualan']) ? $row['tanggal_penjualan'] : ''; ?></td>
            <td><?= isset($row['nama_lengkap']) ? $row['nama_lengkap'] : ''; ?></td>        
            <td><?= isset($row['nama_produk']) ? $row['nama_produk'] : ''; ?></td>
            <td><?= isset($row['nama_pelanggan']) ? $row['nama_pelanggan'] : ''; ?></td>
            <td>Rp <?= isset($row['total']) ? number_format($row['total'], 0) : ''; ?></td>
            <td><?= isset($row['keterangan']) ? $row['keterangan'] : ''; ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>

                            </table>
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
        </div>
    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        // Fungsi untuk menampilkan dialog konfirmasi sebelum menghapus stok
        function confirmDelete(id) {
            // Tampilkan dialog konfirmasi
            var result = confirm("Apakah Anda yakin ingin menghapus stok ini?");
            // Jika pengguna menekan tombol OK
            if (result) {
                // Redirect ke halaman hapus_stok.php dengan menyertakan ID stok yang akan dihapus
                window.location.href = "hapus_stok.php?id=" + id;
            }
        }
    </script>
</body>

</html>