-- Pembaruan database Adukan! (4 Nov 2026) untuk MySQL / InfinityFree.
-- Jalankan SEKALI di phpMyAdmin: pilih database -> tab SQL -> tempel semua -> Go.
-- Isi data lama tidak berubah.

SET NAMES utf8mb4;

-- Jurusan teknisi
ALTER TABLE `users` ADD COLUMN `jurusan` VARCHAR(60) NULL AFTER `role`;

-- Salinan nama teknisi di laporan (riwayat tetap utuh walau akun teknisi dihapus)
ALTER TABLE `pengaduan` ADD COLUMN `teknisi_nama` VARCHAR(100) NULL;
UPDATE `pengaduan` p JOIN `users` u ON u.id = p.teknisi_id SET p.teknisi_nama = u.name;

-- Permintaan lupa kata sandi
CREATE TABLE `reset_sandi` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `kode` VARCHAR(255) NULL,
  `kadaluarsa_at` TIMESTAMP NULL DEFAULT NULL,
  `percobaan` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reset_sandi_user_id_unique` (`user_id`),
  CONSTRAINT `reset_sandi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Catat di tabel migrations supaya `php artisan migrate` di masa depan tidak mengulanginya
INSERT INTO `migrations` (`migration`, `batch`) SELECT '2026_11_04_000000_add_jurusan_dan_teknisi_nama', COALESCE(MAX(`batch`), 0) + 1 FROM `migrations`;
INSERT INTO `migrations` (`migration`, `batch`) SELECT '2026_11_04_000100_create_reset_sandi_table', COALESCE(MAX(`batch`), 0) FROM `migrations`;
