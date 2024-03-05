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

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {
    // Panggil fungsi addToko() untuk menyimpan data
    $data = [
        'nama_toko' => $_POST['nama_toko'],
        'alamat' => $_POST['alamat'],
        'no_hp' => $_POST['no_hp'],
    ];

    $result = addToko($data);

    if ($result) {
        echo '<script>alert("Data toko berhasil disimpan");</script>';
        // Refresh halaman untuk memperbarui tabel
        echo '<script>window.location.href = "toko.php";</script>';
    } else {
        echo '<script>alert("Gagal menambahkan data toko");</script>';
    }
}
// Query untuk mengambil data produk dari tabel
$query = "SELECT * FROM toko";
$result = mysqli_query($conn, $query);

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
                    <h1 class="mt-4">Dashboard Admin</h1>
                    <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Selamat datang
    <?php 
    if (isset($_SESSION['nama_lengkap'])) {
        echo $_SESSION['nama_lengkap']; 
    } else {
        echo "Nama lengkap tidak tersedia"; // Tambahkan pesan default jika nama lengkap tidak tersedia
    }
    ?>
</li>
                    </ol>     
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertToko">
                                Insert Toko
                            </button>
                        </div>
                    </div>               
                    <!-- Table for Data Toko -->
<div class="row mt-4">
    <div class="col-12">
        <h2>Daftar Toko</h2>
        <table class="table table-striped table-bordered">
            <thead class="bg-dark text-white">
                <tr>
                    <th scope="col">Nama Toko</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">No.Tlpn</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop untuk menampilkan data toko -->
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['nama_toko']; ?></td>
                        <td><?php echo $row['alamat']; ?></td>
                        <td><?php echo $row['no_hp']; ?></td>
                        <td>
                            <!-- Icon edit -->
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['toko_id']; ?>"><i class="fas fa-edit"></i></a>
                            <!-- Icon hapus -->
                            <a href="#" onclick="confirmDelete(<?php echo $row['toko_id']; ?>)"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>

                    <!-- Modal Edit Toko -->
                    <div class="modal fade" id="editModal<?php echo $row['toko_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title" id="editModalLabel">Edit Toko</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body bg-dark text-white">
                                    <form action="edit.php?id=<?php echo $row['toko_id']; ?>" method="post">
                                        <div class="mb-3">
                                            <label for="editNamaToko" class="form-label">Nama Toko</label>
                                            <input type="text" class="form-control" id="editNamaToko" name="editNamaToko" value="<?php echo $row['nama_toko']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="editAlamat" class="form-label">Alamat</label>
                                            <input type="text" class="form-control" id="editAlamat" name="editAlamat" value="<?php echo $row['alamat']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="editNoTlpn" class="form-label">No. Tlpn</label>
                                            <input type="text" class="form-control" id="editNoTlpn" name="editNoTlpn" value="<?php echo $row['no_hp']; ?>">
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary" name="editSubmit">Edit</button>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <!-- Tutup loop -->
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

<!-- Modal for Insert Toko -->
<div class="modal fade" id="insertToko" tabindex="-1" aria-labelledby="insertToko" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="insertToko">Insert Toko</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-dark text-white">
                <!-- Form for adding store -->
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="nama_toko" class="form-label">Nama Toko</label>
                        <input type="text" class="form-control" id="nama_toko" name="nama_toko">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp">
                    </div>
                    <!-- Pastikan nama tombol submit adalah "submit" -->
                    <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
</form>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
    // Fungsi untuk menampilkan dialog konfirmasi sebelum menghapus toko
    function confirmDelete(id) {
        // Tampilkan dialog konfirmasi
        var result = confirm("Apakah Anda yakin ingin menghapus toko ini?");
        // Jika pengguna menekan tombol OK
        if (result) {
            // Redirect ke halaman hapus.php dengan menyertakan ID toko yang akan dihapus
            window.location.href = "hapus.php?id=" + id;
        }
    }
</script>
</body>
</html>
