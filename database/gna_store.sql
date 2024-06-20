-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Jun 2024 pada 06.59
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gna_store`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama_lengkap` varchar(1000) NOT NULL,
  `nomor_telepon` varchar(20) NOT NULL,
  `edit_logo` tinyint(1) NOT NULL,
  `edit_banner` tinyint(1) NOT NULL,
  `edit_produk` tinyint(1) NOT NULL,
  `edit_footer` tinyint(1) NOT NULL,
  `notifikasi` tinyint(1) NOT NULL,
  `edit_dekorasi_toko` tinyint(1) NOT NULL,
  `tambahkan_produk` tinyint(1) NOT NULL,
  `hapus_produk` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `email`, `nama_lengkap`, `nomor_telepon`, `edit_logo`, `edit_banner`, `edit_produk`, `edit_footer`, `notifikasi`, `edit_dekorasi_toko`, `tambahkan_produk`, `hapus_produk`) VALUES
(1, 'mhs_lutfi', 'lutfihs99', 'lutfihs18@gmail.com', 'lutfi halimatu sa\'diah', '082215266439', 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `banners`
--

CREATE TABLE `banners` (
  `id` int(255) NOT NULL,
  `image` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `banners`
--

INSERT INTO `banners` (`id`, `image`) VALUES
(4, '../gambar/banner1.png'),
(5, '../gambar/banner2.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `footer_content`
--

CREATE TABLE `footer_content` (
  `id` int(255) NOT NULL,
  `section` varchar(10000) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `footer_content`
--

INSERT INTO `footer_content` (`id`, `section`, `content`) VALUES
(1, 'layanan-kami', 'Menerima layanan custom design\n\nSilakan ajukan pertanyaan Anda melalui kontak '),
(2, 'hubungi-kami', '<p>Garut - Jawa Barat</p><p>gnaid.hijab@gmail.com</p><p>+62 821-1500-2654</p>');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(255) NOT NULL,
  `nama_kategori` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'HIJAB'),
(2, 'DRESS MUSLIM'),
(3, 'INNER'),
(4, 'BEST SELLER'),
(5, 'MUKENA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(255) NOT NULL,
  `id_users` int(255) NOT NULL,
  `id_produk` int(255) NOT NULL,
  `kuantitas` int(255) NOT NULL,
  `added_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id_orders` int(255) NOT NULL,
  `id_users` int(255) NOT NULL,
  `id_keranjang` int(255) NOT NULL,
  `tanggal_pesanan` datetime(6) NOT NULL,
  `status_pesanan` enum('paid','unpaid') DEFAULT 'unpaid',
  `total_harga` decimal(65,0) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `kurir` varchar(50) NOT NULL,
  `ongkos_kirim` decimal(10,2) NOT NULL,
  `no_resi` varchar(50) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `metode_pembayaran` varchar(255) NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(255) NOT NULL,
  `id_users` int(255) NOT NULL,
  `id_produk` int(255) NOT NULL,
  `harga` decimal(65,0) NOT NULL,
  `kuantitas` int(255) NOT NULL,
  `nama_produk` varchar(1000) NOT NULL,
  `kode_produk` varchar(50) NOT NULL,
  `berat` decimal(10,2) NOT NULL,
  `diskon` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `id_users`, `id_produk`, `harga`, `kuantitas`, `nama_produk`, `kode_produk`, `berat`, `diskon`, `subtotal`) VALUES
(1, 13, 13, '0', 0, '', '', '0.00', '0.00', '0.00'),
(2, 13, 13, '0', 0, '', '', '0.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(255) NOT NULL,
  `gambar` varchar(1000) NOT NULL,
  `nama_produk` varchar(1000) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(65,0) NOT NULL,
  `stok` int(255) NOT NULL,
  `id_kategori` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `gambar`, `nama_produk`, `deskripsi`, `harga`, `stok`, `id_kategori`) VALUES
(13, '../gambar/ameena.jpg', 'AMEENA BERGO ZIPPER', 'Ameena Bergo Zipper :\r\nBahan : Airflow Crinckle\r\nPanjang bawah dagu : 55 cm\r\nPanjang belakang : 105 cm\r\nAmeena zipper sudah dilengkapi resleting di bawah dagu sehingga bisa menyesuaikan ukuran muka.\r\nBahan crinckle ringan, tekstur bahan agak kusut, sterch tapi tidak mudah melar\r\nterdapat 8 varian warna yaa sister, happy shopping 😘', '85000', 100, 1),
(14, '../gambar/arumi.jpg', 'ARUMI DRESS [KOREAN LOOK]', 'Material : Arumi CrinckleMotif kotak-kotakBahan strech tapi tidak mudah melar, terlihat agak sedikit kusut (bawaan dari bahan)Size M = LD 96 cm, PB= 138 CMsize L = LD= 100 CM, PB = 138 CM', '260000', 100, 2),
(16, '../gambar/cinderella.jpg', 'CINERELLA DRESS ONE SET', 'Cinderella Dress  Material : Babydoll Cuttingan dress dibuat anggun seperti cinderella ALL SIZE LD // PB   96 cm // 138 cm  Terdapat bagian kerut di tangan, dilengkapi kancing pada bagian ujung tangan Harga sudah termasuk hijab segi 4 (size 130*130 cm polos tidak pakai resleting)', '350000', 0, 2),
(17, '../gambar/curve.png', 'JENNAIRA KHIMAR ZIPPER CURVE', 'JENAIRA KHIMAR ZIPPER CURVE\r\n\r\nJenaira Khimar curve inovasi dari jennira Khimar.\r\nbentuk Khimar bagian bawah oval sehingga membuat hijab lbh stylish\r\n\r\nSpesifikasi Produk :\r\n- Bahan khimar : Babydoll premium \r\n- Size : 140 x 140cm ( sebelum di jahit)\r\n- Finishing Tepi : Jahitan Rapih', '80000', 0, 1),
(18, '../gambar/fanibergo.jpg', 'FANI BERGO ZIPPER JERSEY PREMIUM SPORTY', 'FANI BERGO ZIPPER : \r\n\r\nBahan Jersey Premium ', '55000', 0, 1),
(19, '../gambar/fatima.jpeg', 'FATIMA PRAYER SET (SILK)', 'Assalamualaikum sister😘Perkenalkan Koleksi terbaru SPESIAL IDUL FITRI  BY G. N. A. IDFATIMA PRAYERS SET Bahan : Armani SilkPanjang atasan mukena : panjang bawah dagu 88 cm panjang belakang 142 cm panjang bawahan mukena (rok) : 113 cm bahan silkMukena cantik ini sudah dilengkapi Zipper yaa sister, jadi dapat di sesuaikan dengan lingkar muka ..1 set mukena ini juga sudah di lengkapi tas dan sajadah mini .. ', '350000', 0, 5),
(20, '../gambar/humaira.jpg', 'Humaira Dress Crinkle Airflow Umroh Set', 'Humaira dress:\r\nMaterial : Crinckle Airflow\r\nCuttingan dress membentuk A line \r\nTersedia size fit to M dan fit to L \r\nsudah dilengkapi resleting bagian depan dada\r\n\r\nsize \r\nLD 96 CM, PB 138 CM\r\n\r\nsatu set sudah termasuk khimar Humaira yang di lengkapi resleting bawah dagu', '280000', 0, 2),
(21, '../gambar/innerninja.jpg', 'Inner Ninja Kancing', 'Bahan kaos rayonBisa dijadiin masker, ada kancing di bagian pinggirnya untuk memudahkan mengikat ke belakang', '35000', 0, 3),
(22, '../gambar/jennadaily.jpg', 'Jenna Daily Zipper Polycotton', 'Daily zipper \r\n\r\nSpesifikasi Produk :\r\n- Bahan : Polycotton\r\n- Size : 1,10 x 1,10 m \r\n-Detail : Dilengkapi Resleting depan pendek', '37000', 0, 1),
(23, '../gambar/jennaira1.jpg', 'Jennaira Khimar Zipper Ver1', 'Jenaira khimar zipper\r\nterbuat dari bahan Baby doll premium,\r\npanjang segi empat 130 cm x 130 cm, dilengkapi resleting panjang\r\nJenaira ini versi ke 1 size 130, resleting panjang sehingga tidak bisa disematkan kepundak ya\r\nTerdapat banyak pilihan warna 😍', '75000', 0, 4),
(24, '../gambar/jennaira2.jpg', 'Jennaira Khimar Zipper Syar\'i Versi 2 (150 CM*150CM)', 'JENAIRA KHIMAR VERSI 2\r\n\r\nBahan khimar : Babydoll premium\r\nSize = Panjang 150 cm* Lebar 150 cm (sebelum dipotong)\r\nsudah dilengkapi resleting Pendek dibagian bawah dagu sehingga memudahkan sister beraktifitas\r\nResleting pendek, sehingga bisa di buat beberapa style hijab dan bisa disematkan dipundak', '85000', 0, 4),
(25, '../gambar/jennaira3.jpg', 'Jennaira Khimar Zipper Versi KE 3 (130*130)', 'JENAIRA KHIMAR ZIPPER VERSI KE 3\r\n\r\nBahan khimar : Babydoll premium \r\n\r\nSize = Panjang 130 cm x Lebar 130 cm (Sebelum di jahit)\r\n\r\ntoleransi jahit 1-2cm\r\n\r\nResleting pendek sehingga bisa di buat beberapa style hijab. \r\n\r\n\r\nmohon berhati\" saat pemesanan :\r\nBerikut perbedaan jenaira :\r\n\r\nJenaira versi ke 1 : size panjang 130* lebar 130 cm, Resleting Panjang, tidak bisa disematkan ke pundak\r\n\r\nJenaira versi ke 2 : Panjang 150* lebar 150 cm, resleting pendek , bisa disematkan di pundak\r\n\r\nJenaira versi ke 3 : panjang 130* lebar 130, resleting pendek, bisa di sematkan di pundak', '75000', 0, 4),
(26, '../gambar/syafiraciput.jpg', 'Syafira Ciput Kaos Cepol', 'Bahan : Ciput Kaos Premium\r\n-Terdapat lubang telinga sehingga tidak membuat sakit\r\n- terdapat tempat cepol rambut bagian belakang sehingga rambut tertata rapuh di dalam. \r\n- bisa ditali ke belakang', '30000', 0, 3),
(27, '../gambar/madinapleats.jpg', 'Madina Pleats (Cuci gudang)', 'Material : Babydoll \r\n\r\nTerdapat 2 size : \r\nSize 185*150 cm (Sebelum di plisket)\r\nSize 1858120 cm (Sebelum di plisket)\r\n\r\nKarena ini produk cuci gudang dan stok barangnya tinggal sedikit maka warna yang akan dikirim kan random :)', '50000', 0, 1),
(28, '../gambar/saleema.jpg', 'Saleema Pashmina Oval', 'SAKINA PASHMINA RAYON \r\n\r\nMaterial : Flowy Rayon Premium\r\nSize : \r\nBentuk belakang oval dan mleyot ketika dipakai. ', '85000', 0, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `toko`
--

CREATE TABLE `toko` (
  `id` int(255) NOT NULL,
  `nama` varchar(1000) NOT NULL,
  `logo_path` varchar(1000) NOT NULL,
  `no_telepon` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `toko`
--

INSERT INTO `toko` (`id`, `nama`, `logo_path`, `no_telepon`) VALUES
(1, 'G.N.A.ID', '../gambar/logojadi.jpg', '+62 821-1500-2654');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_users` int(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama_lengkap` varchar(1000) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `alamat_lengkap` varchar(1000) NOT NULL,
  `no_telepon` varchar(1000) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kota` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_users`, `password`, `nama_lengkap`, `email`, `alamat_lengkap`, `no_telepon`, `tanggal_lahir`, `jenis_kelamin`, `provinsi`, `kecamatan`, `kota`) VALUES
(6, 'lutfihs99', 'lutfi halimatu sa\'diah', 'bookishkave@gmail.com', 'sdad', '082215266439', '1999-07-18', 'Perempuan', 'jawa barat', 'sukaresmi', 'garut');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `footer_content`
--
ALTER TABLE `footer_content`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_orders`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indeks untuk tabel `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `footer_content`
--
ALTER TABLE `footer_content`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `toko`
--
ALTER TABLE `toko`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
