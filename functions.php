<?php
$conn = new mysqli('localhost', 'root', '', '_kasir');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengkonekan untuk register
function register($data)
{
    global $conn;

    // Ambil data dari form
    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);
    $email = mysqli_real_escape_string($conn, $data["email"]);
    $nama_lengkap = isset($data["nama_lengkap"]) ? mysqli_real_escape_string($conn, $data["nama_lengkap"]) : "";
    $alamat = mysqli_real_escape_string($conn, $data["alamat"]);
    $access_level = mysqli_real_escape_string($conn, $data["access_level"]);

    // Pastikan semua data telah diisi
    if (empty($username) || empty($password) || empty($password2) || empty($email) || empty($alamat) || empty($access_level)) {
        echo '<script>alert("Mohon lengkapi semua data!");</script>';
        return false;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo '<script>alert("Konfirmasi Tidak Sesuai !");</script>';
        return false;
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan data ke database
    $query = "INSERT INTO users (user_id, toko_id, username, password, email, nama_lengkap, alamat, access_level) 
              VALUES (NULL, NULL, '$username', '$password', '$email', '$nama_lengkap', '$alamat', '$access_level')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Fungsi untuk menambahkan data toko ke dalam tabel
function addToko($data)
{
    global $conn;

    $nama_toko = $data['nama_toko'];
    $alamat = $data['alamat'];
    $no_hp = $data['no_hp'];

    $sql = "INSERT INTO toko (nama_toko, alamat, no_hp) VALUES ('$nama_toko', '$alamat', '$no_hp')";

    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        mysqli_close($conn);
        return false;
    }
}

// Fungsi untuk menambahkan data kategori ke dalam tabel
function addKategori($data)
{
    global $conn;

    $nama_kategori = $data['nama_kategori'];

    $sql = "INSERT INTO produk_kategori (nama_kategori) VALUES ('$nama_kategori')";

    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        mysqli_close($conn);
        return false;
    }
}

function addProduk($data)
{
    global $conn;

    // Ambil data dari form
    $nama_produk = mysqli_real_escape_string($conn, $data["nama_produk"]);
    $satuan = mysqli_real_escape_string($conn, $data["satuan"]);
    $stok = mysqli_real_escape_string($conn, $data["stok"]);
    $harga_beli = mysqli_real_escape_string($conn, $data["harga_beli"]);
    $harga_jual = mysqli_real_escape_string($conn, $data["harga_jual"]);
    $toko_id = mysqli_real_escape_string($conn, $data["toko_id"]);
    $kategori_id = mysqli_real_escape_string($conn, $data["kategori_id"]);
    $kode_produk = mysqli_real_escape_string($conn, $data["kode_produk"]);

    // Query untuk menyimpan data ke dalam tabel produk
    $query = "INSERT INTO produk (toko_id, nama_produk, kategori_id, satuan, harga_beli, harga_jual, kode_produk, stok) 
              VALUES ('$toko_id', '$nama_produk', '$kategori_id', '$satuan', '$harga_beli', '$harga_jual', '$kode_produk', '$stok')";

    // Jalankan query untuk menyimpan data
    $result = mysqli_query($conn, $query);

    // Periksa apakah penyimpanan data berhasil atau tidak
    if ($result) {
        // Jika berhasil, kembalikan jumlah baris yang terpengaruh
        return mysqli_affected_rows($conn);
    } else {
        // Jika gagal, kembalikan nilai false
        return false;
    }
}

function addSupplier($data)
{
    global $conn;

    // Ambil data dari form
    $toko_id = $data['toko_id'];
    $nama_supplier = mysqli_real_escape_string($conn, $data["nama_supplier"]);
    $no_hp = mysqli_real_escape_string($conn, $data["no_hp"]);
    $alamat = mysqli_real_escape_string($conn, $data["alamat"]);

    // Query untuk menyimpan data ke dalam tabel supplier
    $query = "INSERT INTO supplier (toko_id, nama_supplier, no_hp, alamat) 
              VALUES ('$toko_id', '$nama_supplier', '$no_hp', '$alamat')";

    // Jalankan query untuk menyimpan data
    $result = mysqli_query($conn, $query);

    // Periksa apakah penyimpanan data berhasil atau tidak
    if ($result) {
        // Jika berhasil, kembalikan jumlah baris yang terpengaruh
        return mysqli_affected_rows($conn);
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
        return false;
    }
}

// Fungsi untuk menambahkan data pelanggan ke dalam tabel
function addPelanggan($data)
{
    global $conn;

    // Ambil data dari form
    $toko_id = $data['toko_id'];
    $nama_pelanggan = mysqli_real_escape_string($conn, $data["nama_pelanggan"]);
    $alamat = mysqli_real_escape_string($conn, $data["alamat"]);
    $no_hp = mysqli_real_escape_string($conn, $data["no_hp"]);

    // Query untuk menyimpan data ke dalam tabel supplier
    $query = "INSERT INTO pelanggan (toko_id, nama_pelanggan, alamat, no_hp) 
              VALUES ('$toko_id', '$nama_pelanggan', '$alamat', '$no_hp')";

    // Jalankan query untuk menyimpan data
    $result = mysqli_query($conn, $query);

    // Periksa apakah penyimpanan data berhasil atau tidak
    if ($result) {
        // Jika berhasil, kembalikan jumlah baris yang terpengaruh
        return mysqli_affected_rows($conn);
    } else {
        // Jika gagal, kembalikan nilai false
        return false;
    }
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function untuk mencari produk berdasarkan keyword
function cari_produk($keyword) {
    global $conn;

    // Pastikan koneksi ke database berhasil
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape karakter khusus pada keyword
    $keyword = $conn->real_escape_string($keyword);

    // Query pencarian produk berdasarkan keyword
    $query = "SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%'";
    $result = $conn->query($query);

    // Inisialisasi array untuk menyimpan hasil pencarian
    $produk = [];

    // Periksa apakah query berhasil dieksekusi
    if ($result) {
        // Loop untuk mengambil setiap baris hasil query
        while ($row = $result->fetch_assoc()) {
            // Tambahkan data produk ke dalam array
            $produk[] = $row;
        }
    }
    // Kembalikan hasil pencarian produk
    return $produk;
}


// Fungsi untuk mengambil data nama toko dari tabel toko
function getNamaTokoOptions() {
    global $conn;

    $query = "SELECT toko_id, nama_toko FROM toko";
    $result = mysqli_query($conn, $query);

    $options = '';

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $toko_id = $row['toko_id'];
            $nama_toko = $row['nama_toko'];
            $options .= "<option value='$toko_id'>$nama_toko</option>";
        }
    }

    return $options;
}

// Fungsi untuk menambahkan order ke dalam tabel
function addOrder($data)
{
    global $conn;

    // Ambil data dari form
    $nama_pelanggan = mysqli_real_escape_string($conn, $data["nama_pelanggan"]);
    $alamat = mysqli_real_escape_string($conn, $data["alamat"]);
    $no_hp = mysqli_real_escape_string($conn, $data["no_hp"]);
    $toko_id = mysqli_real_escape_string($conn, $data["toko_id"]);
    $nama_produk = mysqli_real_escape_string($conn, $data["nama_produk"]);
    $harga_jual = mysqli_real_escape_string($conn, $data["harga_jual"]);

    // Query untuk menyimpan data ke dalam tabel order
    $query = "INSERT INTO order (nama_pelanggan, alamat, no_hp, toko_id, nama_produk, harga_jual) 
              VALUES ('$nama_pelanggan', '$alamat', '$no_hp', '$toko_id', '$nama_produk', '$harga_jual')";

    // Jalankan query untuk menyimpan data
    $result = mysqli_query($conn, $query);

    // Periksa apakah penyimpanan data berhasil atau tidak
    if ($result) {
        // Jika berhasil, kembalikan jumlah baris yang terpengaruh
        return mysqli_affected_rows($conn);
    } else {
        // Jika gagal, kembalikan nilai false
        return false;
    }
}

// Fungsi untuk mendapatkan data produk berdasarkan ID
function getProductData($productId) {
    global $conn;

    $productId = mysqli_real_escape_string($conn, $productId);

    $query = "SELECT * FROM produk WHERE produk_id = '$productId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

function getLatestInvoiceNumber() {
    global $conn;
    $query = "SELECT MAX(no_faktur) AS max_invoice FROM pembelian";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $latest_invoice_number = $row['max_invoice'];
    return $latest_invoice_number;
}

function incrementInvoiceNumber($latest_invoice_number) {
    $invoice_number_array = explode("-", $latest_invoice_number);
    $number_part = intval($invoice_number_array[1]);
    $next_number_part = $number_part + 1;
    $padded_next_number = str_pad($next_number_part, 3, '0', STR_PAD_LEFT); // Mengisi nomor faktur dengan nol di depan jika perlu
    $next_invoice_number = $invoice_number_array[0] . "-" . $padded_next_number;
    return $next_invoice_number;
}


?>