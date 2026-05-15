-- =====================================================
-- Database: apotek
-- Import via phpMyAdmin (Laragon)
-- =====================================================
DROP DATABASE IF EXISTS `apotek`;
CREATE DATABASE `apotek` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `apotek`;

-- ---------- USER (parent / supertype) ----------
CREATE TABLE `user` (
  `id_user` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(50) NOT NULL,
  `email` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','apoteker','pelanggan') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------- ADMIN ----------
CREATE TABLE `admin` (
  `id_user` INT PRIMARY KEY,
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- APOTEKER ----------
CREATE TABLE `apoteker` (
  `id_user` INT PRIMARY KEY,
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- PELANGGAN ----------
CREATE TABLE `pelanggan` (
  `id_user` INT PRIMARY KEY,
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- PELANGGAN_NO_TELP (multivalued) ----------
CREATE TABLE `pelanggan_no_telp` (
  `id_no_telp` INT AUTO_INCREMENT PRIMARY KEY,
  `no_telp` VARCHAR(20) NOT NULL,
  `id_user_pelanggan` INT NOT NULL,
  FOREIGN KEY (`id_user_pelanggan`) REFERENCES `pelanggan`(`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- OBAT ----------
CREATE TABLE `obat` (
  `id_obat` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_obat` VARCHAR(50) NOT NULL,
  `jenis_obat` VARCHAR(50) NOT NULL,
  `harga` INT NOT NULL,
  `tanggal_kadaluarsa` DATE NOT NULL,
  `status_obat` VARCHAR(50) NOT NULL DEFAULT 'tersedia',
  `stok` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- ---------- KONSULTASI ----------
CREATE TABLE `konsultasi` (
  `id_konsultasi` INT AUTO_INCREMENT PRIMARY KEY,
  `tanggal` DATE NOT NULL,
  `catatan` VARCHAR(100),
  `id_user_apoteker` INT NOT NULL,
  `id_user_pelanggan` INT NOT NULL,
  FOREIGN KEY (`id_user_apoteker`) REFERENCES `apoteker`(`id_user`),
  FOREIGN KEY (`id_user_pelanggan`) REFERENCES `pelanggan`(`id_user`)
) ENGINE=InnoDB;

-- ---------- DETAIL_KONSULTASI ----------
CREATE TABLE `detail_konsultasi` (
  `id_detail_konsultasi` INT AUTO_INCREMENT PRIMARY KEY,
  `id_konsultasi` INT NOT NULL,
  `id_obat` INT NOT NULL,
  FOREIGN KEY (`id_konsultasi`) REFERENCES `konsultasi`(`id_konsultasi`) ON DELETE CASCADE,
  FOREIGN KEY (`id_obat`) REFERENCES `obat`(`id_obat`)
) ENGINE=InnoDB;

-- ---------- PESANAN ----------
CREATE TABLE `pesanan` (
  `id_pesanan` INT AUTO_INCREMENT PRIMARY KEY,
  `tanggal_pesanan` DATE NOT NULL,
  `status_pesanan` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `id_user_pelanggan` INT NOT NULL,
  FOREIGN KEY (`id_user_pelanggan`) REFERENCES `pelanggan`(`id_user`)
) ENGINE=InnoDB;

-- ---------- DETAIL_PESANAN ----------
CREATE TABLE `detail_pesanan` (
  `id_obat` INT NOT NULL,
  `id_pesanan` INT NOT NULL,
  `jumlah` INT NOT NULL,
  PRIMARY KEY (`id_obat`,`id_pesanan`),
  FOREIGN KEY (`id_obat`) REFERENCES `obat`(`id_obat`),
  FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan`(`id_pesanan`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- PENGIRIMAN ----------
CREATE TABLE `pengiriman` (
  `id_pengiriman` INT AUTO_INCREMENT PRIMARY KEY,
  `alamat_ngirim` VARCHAR(100) NOT NULL,
  `ongkir` VARCHAR(50) NOT NULL,
  `status_ngirim` VARCHAR(50) NOT NULL DEFAULT 'diproses',
  `id_pesanan` INT NOT NULL,
  FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan`(`id_pesanan`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- PEMBAYARAN ----------
CREATE TABLE `pembayaran` (
  `id_pembayaran` INT AUTO_INCREMENT PRIMARY KEY,
  `metode` VARCHAR(50) NOT NULL,
  `tanggal_bayar` DATE NOT NULL,
  `status_bayar` VARCHAR(50) NOT NULL DEFAULT 'belum',
  `id_pesanan` INT NOT NULL,
  FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan`(`id_pesanan`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- SAMPLE DATA
-- Password default semua user: "password123"
-- Hash bcrypt: $2b$10$U/rHgNv70rE4iawYW5tLuu3DJK/0LVb8hLhYuLd31fBLrpTLU6a.O
-- =====================================================
INSERT INTO `user` (`nama`,`email`,`password`,`role`) VALUES
('Admin Apotek','admin@apotek.com','$2b$10$U/rHgNv70rE4iawYW5tLuu3DJK/0LVb8hLhYuLd31fBLrpTLU6a.O','admin'),
('Budi Apoteker','budi@apotek.com','$2b$10$U/rHgNv70rE4iawYW5tLuu3DJK/0LVb8hLhYuLd31fBLrpTLU6a.O','apoteker'),
('Siti Apoteker','siti@apotek.com','$2b$10$U/rHgNv70rE4iawYW5tLuu3DJK/0LVb8hLhYuLd31fBLrpTLU6a.O','apoteker'),
('Andi Pelanggan','andi@mail.com','$2b$10$U/rHgNv70rE4iawYW5tLuu3DJK/0LVb8hLhYuLd31fBLrpTLU6a.O','pelanggan'),
('Rina Pelanggan','rina@mail.com','$2b$10$U/rHgNv70rE4iawYW5tLuu3DJK/0LVb8hLhYuLd31fBLrpTLU6a.O','pelanggan');

INSERT INTO `admin`(`id_user`) VALUES (1);
INSERT INTO `apoteker`(`id_user`) VALUES (2),(3);
INSERT INTO `pelanggan`(`id_user`) VALUES (4),(5);

INSERT INTO `pelanggan_no_telp`(`no_telp`,`id_user_pelanggan`) VALUES
('081234567890',4),('082345678901',4),('083456789012',5);

INSERT INTO `obat`(`nama_obat`,`jenis_obat`,`harga`,`tanggal_kadaluarsa`,`status_obat`,`stok`) VALUES
('Paracetamol 500mg','Tablet',5000,'2026-12-31','tersedia',100),
('Amoxicillin 500mg','Kapsul',12000,'2026-08-30','tersedia',50),
('OBH Combi','Sirup',18000,'2026-06-15','tersedia',30),
('Vitamin C 1000mg','Tablet',25000,'2027-01-20','tersedia',80),
('Antasida','Tablet',7500,'2026-10-10','tersedia',60);

INSERT INTO `pesanan`(`tanggal_pesanan`,`status_pesanan`,`id_user_pelanggan`) VALUES
('2026-05-01','selesai',4),
('2026-05-02','diproses',5);

INSERT INTO `detail_pesanan`(`id_obat`,`id_pesanan`,`jumlah`) VALUES
(1,1,2),(4,1,1),(2,2,1),(5,2,3);

INSERT INTO `pembayaran`(`metode`,`tanggal_bayar`,`status_bayar`,`id_pesanan`) VALUES
('Transfer BCA','2026-05-01','lunas',1),
('COD','2026-05-02','belum',2);

INSERT INTO `pengiriman`(`alamat_ngirim`,`ongkir`,`status_ngirim`,`id_pesanan`) VALUES
('Jl. Merdeka No. 10, Jakarta','15000','terkirim',1),
('Jl. Sudirman No. 5, Bandung','20000','diproses',2);

INSERT INTO `konsultasi`(`tanggal`,`catatan`,`id_user_apoteker`,`id_user_pelanggan`) VALUES
('2026-05-01','Demam ringan, disarankan paracetamol',2,4),
('2026-05-02','Batuk berdahak',3,5);

INSERT INTO `detail_konsultasi`(`id_konsultasi`,`id_obat`) VALUES
(1,1),(1,4),(2,3);
