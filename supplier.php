<?php
require 'functions.php';

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    // Ambil data dari form untuk tabel supplier
    $nama_supplier = $_POST['nama_supplier'];
    $toko_id = $_POST['toko_id']; // Ambil ID toko dari form
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat_supplier'];

    // Query untuk memasukkan data ke dalam tabel supplier
    $query_supplier = "INSERT INTO supplier (nama_supplier, toko_id, no_hp, alamat) VALUES ('$nama_supplier', '$toko_id', '$no_hp', '$alamat')";
    $result_supplier = mysqli_query($conn, $query_supplier);

    if ($result_supplier && $result_produk) {
        echo '<script>alert("Data Supplier berhasil disimpan");</script>';
        echo '<script>window.location.href = "supplier.php";</script>';
    } else {
        echo '<script>alert("Gagal menyimpan data Supplier dan Produk");</script>';
    }
}

$query_toko = "SELECT * FROM toko";
$result_toko = mysqli_query($conn, $query_toko);

$query_supplier = "SELECT * FROM supplier";
$result_supplier = mysqli_query($conn, $query_supplier);

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" crossorigin="anonymous"></script>
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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                Add Supplier
                            </button>
                        </div>
                    </div>
                    <!-- Table for Data Produk -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h2>Data Supplier</h2>
                            <table class="table table-striped table-bordered">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th scope="col">Nama Toko</th>                                        
                                        <th scope="col">Nama Supplier</th>
                                        <th scope="col">No.HP</th>
                                        <th scope="col">Alamat</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result_supplier)) : ?>
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
                                       
                                        ?>
                                        <td><?php echo $nama_toko; ?></td>                                        
                                        <td><?php echo $row['nama_supplier']; ?></td>
                                        <td><?php echo $row['no_hp']; ?></td>
                                        <td><?php echo $row['alamat']; ?></td>
                                        <td>
    <!-- Icon edit -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#editSupplierModal<?php echo $row['supplier_id']; ?>"><i class="fas fa-edit"></i></a>
    <!-- Icon hapus -->
    <a href="#" onclick="confirmDelete(<?php echo $row['supplier_id']; ?>)"><i class="fas fa-trash"></i></a>
</td>

<!-- Modal for Edit Supplier -->
<div class="modal fade" id="editSupplierModal<?php echo $row['supplier_id']; ?>" tabindex="-1" aria-labelledby="editSupplierModalLabel<?php echo $row['supplier_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editSupplierModalLabel<?php echo $row['supplier_id']; ?>">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-dark text-white">
                <!-- Form for editing supplier -->
                <form action="" method="post">
                    <!-- Hidden input field to store supplier_id -->
                    <input type="hidden" name="supplier_id" value="<?php echo $row['supplier_id']; ?>">
                    <div class="mb-3">
                        <label for="editNamaSupplier" class="form-label">Nama Supplier</label>
                        <!-- Set the value of editNamaSupplier to the current nama_supplier -->
                        <input type="text" class="form-control" id="editNamaSupplier" name="editNamaSupplier" value="<?php echo $row['nama_supplier']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editToko" class="form-label">Nama Toko</label>
                        <select class="form-select" id="editToko" name="editToko">
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
                        <label for="editNoHp" class="form-label">No HP</label>
                        <!-- Set the value of editTlpHp to the current no_hp -->
                        <input type="text" class="form-control" id="editNoHp" name="editNoHp" value="<?php echo $row['no_hp']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editAlamatSupplier" class="form-label">Alamat</label>
                        <!-- Set the value of editAlamatSupplier to the current alamat -->
                        <input type="text" class="form-control" id="editAlamatSupplier" name="editAlamatSupplier" value="<?php echo $row['alamat']; ?>">
                    </div>
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

    <!-- Modal for Add Product -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="addSupplierModalLabel">Add Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-dark text-white">
                    <!-- Form for adding supplier -->
                    <form action="" method="post">                                   
                    <div class="mb-3">
                            <label for="nama_supplier" class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" id="nama_supplier" name="nama_supplier">
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
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp">
                        </div>
                        <div class="mb-3">
                            <label for="alamat_supplier" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat_supplier" name="alamat_supplier">
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
    // Fungsi untuk menampilkan dialog konfirmasi sebelum menghapus stok
    function confirmDelete(id) {
        // Tampilkan dialog konfirmasi
        var result = confirm("Apakah Anda yakin ingin menghapus supplier ini?");
        // Jika pengguna menekan tombol OK
        if (result) {
            // Redirect ke halaman hapus_stok.php dengan menyertakan ID stok yang akan dihapus
            window.location.href = "hapus_supplier.php?id=" + id;
        }
    }
</script>

<?php mysqli_free_result($result_toko); ?>
<?php mysqli_free_result($result_supplier); ?>
</body>
</html>
