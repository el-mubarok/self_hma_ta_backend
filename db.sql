-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Jul 2023 pada 10.50
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `self_housing_management`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `play_id` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `billing_session`
--

CREATE TABLE `billing_session` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `billing_session`
--

INSERT INTO `billing_session` (`id`, `admin_id`, `name`, `description`, `status`, `from_date`, `to_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'tagihan bulan Oktober 2023', 'iuran tagihan bulanan', NULL, '2023-10-18', '2023-10-20', '2023-07-22 05:46:25', '2023-07-22 06:05:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `billing_session_time`
--

CREATE TABLE `billing_session_time` (
  `id` int(11) NOT NULL,
  `billing_session_id` int(11) DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  `time_stamp` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `billing_session_time`
--

INSERT INTO `billing_session_time` (`id`, `billing_session_id`, `admin_id`, `time_stamp`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '09:21:00', '2023-07-22 05:49:18', '2023-07-22 06:08:06'),
(3, 1, 1, '13:10:00', '2023-07-22 06:09:59', '2023-07-22 06:09:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_details`
--

CREATE TABLE `payment_details` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `billing_session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `method_name` varchar(255) DEFAULT NULL,
  `method_type` varchar(255) DEFAULT NULL,
  `method_code` varchar(255) DEFAULT NULL,
  `bank_fee` double DEFAULT NULL,
  `ppn_percentage` tinyint(1) DEFAULT 0,
  `ppn_total` double DEFAULT NULL,
  `bill_amount` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL,
  `payment_id` varchar(255) NOT NULL,
  `payment_status_va` int(11) DEFAULT NULL,
  `status_va` varchar(255) DEFAULT NULL COMMENT 'PENDING, INACTIVE, ACTIVE',
  `merchant_code` varchar(10) DEFAULT NULL,
  `va_number` varchar(100) DEFAULT NULL,
  `payment_status_ewallet` int(11) DEFAULT NULL,
  `status_ewallet` varchar(255) DEFAULT NULL,
  `expiry_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `payment_details`
--

INSERT INTO `payment_details` (`id`, `uuid`, `billing_session_id`, `user_id`, `payment_method_id`, `method_name`, `method_type`, `method_code`, `bank_fee`, `ppn_percentage`, `ppn_total`, `bill_amount`, `grand_total`, `payment_id`, `payment_status_va`, `status_va`, `merchant_code`, `va_number`, `payment_status_ewallet`, `status_ewallet`, `expiry_date`, `created_at`, `updated_at`) VALUES
(1, '567364d8-9a42-43eb-b56d-644b192426a1', 1, 1, 9, 'DANA', 'ewallet', 'ID_DANA', NULL, 1, 525, 35000, 35525, 'ewc_f1d7cf51-2650-4757-bd63-b7ace6c4e425', NULL, NULL, NULL, NULL, NULL, 'PENDING', NULL, '2023-07-19 22:32:53', '2023-07-19 22:32:53'),
(2, '7bbfc941-b6e1-490b-9579-eb8edd23767e', 1, 1, 9, 'DANA', 'ewallet', 'ID_DANA', NULL, 1, 525, 35000, 35525, 'ewc_bdeed0e1-575a-4700-917b-d2bd06855cb3', NULL, NULL, NULL, NULL, NULL, 'SUCCEEDED', NULL, '2023-07-19 22:33:32', '2023-07-20 06:53:41'),
(3, '17016f7b-596d-4c7e-8683-4ced2ff3d398', 1, 1, 2, 'Bank Negara Indonesia', 'va', 'BNI', 4000, 0, NULL, 35000, 39000, '64b7451bac2392719cadcb60', NULL, 'SUCCESS', '8808', '8808999900000007', NULL, NULL, NULL, '2023-07-20 07:36:30', '2023-07-20 13:47:50'),
(4, '64929aa1-3730-4d33-8bbf-5c0230cfe3a1', 1, 1, 1, 'Bank Central Asia', 'va', 'BCA', 4440, 0, NULL, 35000, 39440, '9a78d2e5-120b-43d3-a06e-b4021543cf00', NULL, 'SUCCESS', '10766', '107669999000007', NULL, NULL, NULL, '2023-07-20 13:00:30', '2023-07-20 13:23:51'),
(5, '2682f63c-7ffa-48b6-80f3-a4539b73162c', 1, 1, 9, 'DANA', 'ewallet', 'ID_DANA', NULL, 1, 583, 35000, 35583, 'ewc_82596ba1-5143-4892-ac3f-a2943edf899c', NULL, NULL, NULL, NULL, NULL, 'SUCCEEDED', NULL, '2023-07-20 13:29:50', '2023-07-20 13:30:35'),
(6, 'f68aad89-5db3-47df-879c-e9d96d6dd702', 1, 1, 9, 'DANA', 'ewallet', 'ID_DANA', NULL, 1, 583, 35000, 35583, 'ewc_f43ab9c0-0d01-44de-a3d4-0b363cade202', NULL, NULL, NULL, NULL, NULL, 'PENDING', NULL, '2023-07-21 18:17:22', '2023-07-21 18:17:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL COMMENT 'va / ewallet',
  `code` varchar(255) DEFAULT NULL COMMENT 'channel_code for ewallet, code for va',
  `ppn` double NOT NULL,
  `ppn_percentage` tinyint(1) NOT NULL DEFAULT 0,
  `merchant_code` varchar(10) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `payment_method`
--

INSERT INTO `payment_method` (`id`, `name`, `type`, `code`, `ppn`, `ppn_percentage`, `merchant_code`, `is_active`) VALUES
(1, 'Bank Central Asia', 'va', 'BCA', 4000, 0, NULL, 1),
(2, 'Bank Negara Indonesia', 'va', 'BNI', 4000, 0, NULL, 1),
(3, 'Bank Rakyat Indonesia', 'va', 'BRI', 4000, 0, NULL, 1),
(4, 'Bank Syariah Indonesia', 'va', 'BSI', 4000, 0, NULL, 1),
(5, 'Bank CIMB Niaga', 'va', 'CIMB', 4000, 0, NULL, 1),
(6, 'Bank Mandiri', 'va', 'MANDIRI', 4000, 0, NULL, 1),
(7, 'Bank Permata', 'va', 'PERMATA', 4000, 0, NULL, 1),
(8, 'OVO', 'ewallet', 'ID_OVO', 1.5, 1, NULL, 1),
(9, 'DANA', 'ewallet', 'ID_DANA', 1.5, 1, NULL, 1),
(10, 'LinkAja', 'ewallet', 'ID_LINKAJA', 1.5, 1, NULL, 1),
(11, 'ShopeePay', 'ewallet', 'ID_SHOPEEPAY', 2, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_status_ewallet`
--

CREATE TABLE `payment_status_ewallet` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `payment_status_ewallet`
--

INSERT INTO `payment_status_ewallet` (`id`, `code`, `description`) VALUES
(1, 'SUCCEEDED', 'Pembayaran anda berhasil'),
(2, 'PENDING', 'Menunggu pembayaran anda'),
(3, 'FAILED', 'Pembayaran gagal'),
(4, 'VOIDED', NULL),
(5, 'REFUNDED', 'Pembayaran untuk transaksi ini telah dikembalikan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_status_va`
--

CREATE TABLE `payment_status_va` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `payment_status_va`
--

INSERT INTO `payment_status_va` (`id`, `code`, `description`) VALUES
(1, 'PENDING', 'Permintaan anda sedang diproses oleh bank terkait'),
(2, 'ACTIVE', 'Menunggu pembayaran untuk transaksi ini'),
(3, 'INACTIVE', 'Transaksi tidak aktif'),
(4, 'SUCCESS', 'Pembayaran berhasil');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `qr_code` text DEFAULT NULL COMMENT 'base64->encrypt(house_block:house_number)',
  `house_number` varchar(255) DEFAULT NULL,
  `house_block` varchar(255) DEFAULT NULL,
  `default_password` tinyint(1) DEFAULT NULL,
  `account_bri` varchar(255) DEFAULT NULL,
  `account_bri_enabled` tinyint(1) DEFAULT NULL,
  `account_mandiri` varchar(255) DEFAULT NULL,
  `account_mandiri_enabled` tinyint(1) DEFAULT NULL,
  `account_bni` varchar(255) DEFAULT NULL,
  `account_bni_enabled` tinyint(1) DEFAULT NULL,
  `account_permata` varchar(255) DEFAULT NULL,
  `account_permata_enabled` tinyint(1) DEFAULT NULL,
  `account_bca` varchar(255) DEFAULT NULL,
  `account_bca_enabled` tinyint(1) DEFAULT NULL,
  `account_bsi` varchar(255) DEFAULT NULL,
  `account_bsi_enabled` tinyint(1) DEFAULT NULL,
  `play_id` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `full_name`, `phone_number`, `avatar`, `qr_code`, `house_number`, `house_block`, `default_password`, `account_bri`, `account_bri_enabled`, `account_mandiri`, `account_mandiri_enabled`, `account_bni`, `account_bni_enabled`, `account_permata`, `account_permata_enabled`, `account_bca`, `account_bca_enabled`, `account_bsi`, `account_bsi_enabled`, `play_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'test@mail.com', '$2y$10$o7EinTBZG12UFMPUXu3Ts.PAmS03LytBMCVx581Oiuynf.eZ/a1fm', 'test user 1 edited 2', '0817263727', '/', NULL, '13', 'C-3', NULL, '9999000007', NULL, '9999000007', NULL, '999900000007', NULL, '9999000007', NULL, '9999000007', NULL, '99990000007', NULL, NULL, '2023-07-19 01:21:16', '2023-07-22 04:24:34', '2023-07-22 04:24:34');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `billing_session`
--
ALTER TABLE `billing_session`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `billing_session_time`
--
ALTER TABLE `billing_session_time`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payment_status_ewallet`
--
ALTER TABLE `payment_status_ewallet`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payment_status_va`
--
ALTER TABLE `payment_status_va`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `billing_session`
--
ALTER TABLE `billing_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `billing_session_time`
--
ALTER TABLE `billing_session_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `payment_status_ewallet`
--
ALTER TABLE `payment_status_ewallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `payment_status_va`
--
ALTER TABLE `payment_status_va`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
