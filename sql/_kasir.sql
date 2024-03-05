-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2024 at 03:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `_kasir`
--

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `pelanggan_id` int(11) NOT NULL,
  `nama_pelanggan` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`pelanggan_id`, `nama_pelanggan`, `alamat`, `no_hp`, `created_at`) VALUES
(114, 'Dirosatin', 'Banjar', '085221633605', '2024-03-05 09:02:12'),
(115, 'Tazkia Zalfaa Maulidha ', 'Berlin', '085221633605', '2024-03-05 11:58:28'),
(116, 'Peter Parker', 'Kota Bandung Jawa Barat', '098678456346', '2024-03-05 12:02:03'),
(117, 'Malik', 'Jakarta Selatan', '098678456346', '2024-03-05 14:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `pembelian_id` int(11) NOT NULL,
  `toko_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `no_faktur` varchar(15) DEFAULT NULL,
  `tanggal_pembelian` date DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `bayar` int(11) DEFAULT NULL,
  `sisa` int(11) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`pembelian_id`, `toko_id`, `user_id`, `no_faktur`, `tanggal_pembelian`, `supplier_id`, `total`, `bayar`, `sisa`, `keterangan`, `created_at`) VALUES
(9, 4, 10, 'F20240305-001', '2024-03-05', 2, 100000, 200000, 100000, 'Lunas mereun', '2024-03-05 04:45:31'),
(10, 14, 10, 'F20240305-002', '2024-03-05', 2, 5000, 10000, 5000, 'Lunas', '2024-03-05 04:47:44'),
(11, 4, 10, 'F20240305-003', '2024-03-05', 2, 1000000, 1000000, 0, 'Lunas', '2024-03-05 04:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `penjualan_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tanggal_penjualan` date DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `bayar` int(11) DEFAULT NULL,
  `sisa` int(11) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`penjualan_id`, `user_id`, `tanggal_penjualan`, `pelanggan_id`, `total`, `bayar`, `sisa`, `keterangan`, `created_at`) VALUES
(97, 10, '2024-03-05', 114, 5000, 10000, 5000, 'Lunas', '2024-03-05 09:02:12'),
(98, 10, '2024-03-05', 115, 4000, 100000, 96000, 'Lunas', '2024-03-05 11:58:28'),
(99, 10, '2024-03-05', 116, 5000, 12000, 7000, 'Lunas', '2024-03-05 12:02:03'),
(100, 10, '2024-03-05', 117, 24000, 25000, 1000, 'Lunas', '2024-03-05 14:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_detail`
--

CREATE TABLE `penjualan_detail` (
  `penjualan_detail_id` int(11) NOT NULL,
  `penjualan_id` int(11) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan_detail`
--

INSERT INTO `penjualan_detail` (`penjualan_detail_id`, `penjualan_id`, `produk_id`, `qty`, `harga_jual`, `created_at`) VALUES
(81, 97, 54, 1, 5000, '2024-03-05 09:02:12'),
(82, 98, 41, 1, 4000, '2024-03-05 11:58:28'),
(83, 99, 54, 1, 5000, '2024-03-05 12:02:03'),
(84, 100, 69, 1, 8000, '2024-03-05 14:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `produk_id` int(11) NOT NULL,
  `toko_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_beli` int(25) DEFAULT NULL,
  `harga_jual` int(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stok` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`produk_id`, `toko_id`, `nama_produk`, `kategori_id`, `satuan`, `harga_beli`, `harga_jual`, `created_at`, `stok`) VALUES
(41, 4, 'Indomie Goreng ', 7, 'pcs', 3000, 4000, '2024-02-23 04:21:08', 3),
(43, 4, 'Indomie Rebus Bawang Goreng', 7, 'pcs', 3000, 4000, '2024-02-27 06:14:49', 1),
(54, 14, 'Lifebuoy Lemon', 5, 'pcs', 4000, 5000, '2024-03-01 12:54:19', 5),
(64, 14, 'Ultramilk Full Cream', 5, 'pcs', 95000, 10000, '2024-03-05 05:11:19', 6),
(69, 14, 'Clear Cool', 10, 'pcs', 6000, 8000, '2024-03-05 14:17:56', 49);

-- --------------------------------------------------------

--
-- Table structure for table `produk_kategori`
--

CREATE TABLE `produk_kategori` (
  `kategori_id` int(11) NOT NULL,
  `nama_kategori` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk_kategori`
--

INSERT INTO `produk_kategori` (`kategori_id`, `nama_kategori`, `created_at`) VALUES
(5, 'Sabun', '2024-02-10 13:39:30'),
(7, 'Makanan', '2024-02-20 02:06:13'),
(8, 'Minuman ', '2024-03-01 12:18:40'),
(9, 'Odol', '2024-03-05 14:08:19'),
(10, 'Shampo', '2024-03-05 14:15:28');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `toko_id` int(11) DEFAULT NULL,
  `nama_supplier` varchar(50) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `toko_id`, `nama_supplier`, `no_hp`, `alamat`, `created_at`) VALUES
(2, 4, 'Steve Rogers', '089345678234', 'Jakarta Selatan', '2024-02-14 07:56:04'),
(27, 14, 'Tony Stark', '098678456345', 'Bandung Jawa Barat ', '2024-03-01 12:51:26');

-- --------------------------------------------------------

--
-- Table structure for table `toko`
--

CREATE TABLE `toko` (
  `toko_id` int(11) NOT NULL,
  `nama_toko` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `toko`
--

INSERT INTO `toko` (`toko_id`, `nama_toko`, `alamat`, `no_hp`, `created_at`) VALUES
(4, 'INDOMIE', 'Tanggerang Selatan', '(098)765843458', '2024-02-09 15:04:35'),
(14, 'Unilever Indonesia', 'Jakarta Barat', '098678456344', '2024-03-01 12:17:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `toko_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `access_level` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `toko_id`, `username`, `password`, `email`, `nama_lengkap`, `alamat`, `access_level`, `created_at`) VALUES
(10, NULL, 'admin', '$2y$10$ilkLA4UsageMRivAEWljhO.XwU.UqszE8PgRxrRJksthwn6ksGLq6', 'admin@gmail.com', 'Tazkia Zalfaa Maulidha', 'Banjar', 'admin', '2024-01-26 02:48:20'),
(15, NULL, 'kasir', '$2y$10$Ft0ZHjyEQ09LVhfum3pZfOZq10D/PTceUPniToCtkmdY4dJXKPj3q', 'kasir@gmail.com', 'AFAA', 'Banjar', 'kasir', '2024-02-13 01:34:58'),
(16, NULL, 'zalfaa', '$2y$10$DDGEoy0sxz0hQs8.qpVkAOjIxoLPWLNv9U1n9h.O1Lx9gd4PEE8aq', 'tazkiazalfaam@gmail.com', 'Zalfaa', 'Banjar', 'kasir', '2024-03-03 21:19:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`pelanggan_id`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`pembelian_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `toko_id` (`toko_id`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`penjualan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  ADD PRIMARY KEY (`penjualan_detail_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`produk_id`),
  ADD KEY `toko_id` (`toko_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `produk_kategori`
--
ALTER TABLE `produk_kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`toko_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `pelanggan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `pembelian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `penjualan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  MODIFY `penjualan_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `produk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `produk_kategori`
--
ALTER TABLE `produk_kategori`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `toko`
--
ALTER TABLE `toko`
  MODIFY `toko_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
