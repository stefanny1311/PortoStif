-- =====================================================================
-- DesainHub — Marketplace Jasa Desain Logo & Poster
-- Database: MySQL 8+ | Engine: InnoDB | Charset: utf8mb4
-- =====================================================================

CREATE DATABASE IF NOT EXISTS desainhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE desainhub;

-- ---------------------------------------------------------------------
-- 1. categories — kategori layanan (Logo, Poster, Brand Identity, dll)
-- ---------------------------------------------------------------------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    icon VARCHAR(100) DEFAULT NULL,       -- nama icon bootstrap-icons
    deskripsi VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 2. users — akun untuk semua role (customer, designer, admin)
-- ---------------------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,             -- password_hash()
    no_telepon VARCHAR(20) DEFAULT NULL,
    avatar VARCHAR(255) DEFAULT 'default-avatar.png',
    role ENUM('customer','designer','admin') NOT NULL DEFAULT 'customer',
    status ENUM('active','suspended','pending') NOT NULL DEFAULT 'active',
    reset_token VARCHAR(255) DEFAULT NULL,      -- fitur forgot password
    reset_token_expires DATETIME DEFAULT NULL,
    email_verified_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 3. designers — profil tambahan untuk user berrole designer
-- ---------------------------------------------------------------------
CREATE TABLE designers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    spesialisasi VARCHAR(150) DEFAULT NULL,     -- mis: Logo & Brand Identity
    bio TEXT DEFAULT NULL,
    pengalaman_tahun INT DEFAULT 0,
    rating_rata DECIMAL(2,1) DEFAULT 0.0,
    jumlah_project INT DEFAULT 0,
    saldo DECIMAL(12,2) DEFAULT 0.00,           -- pendapatan yang bisa withdraw
    verified TINYINT(1) DEFAULT 0,              -- badge verified
    verified_at DATETIME DEFAULT NULL,
    dokumen_verifikasi VARCHAR(255) DEFAULT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 4. portfolio — karya designer (untuk galeri & bukti kredibilitas)
-- ---------------------------------------------------------------------
CREATE TABLE portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    designer_id INT NOT NULL,
    category_id INT NOT NULL,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT DEFAULT NULL,
    gambar VARCHAR(255) NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending', -- verifikasi admin
    dilihat INT DEFAULT 0,
    disukai INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (designer_id) REFERENCES designers(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 5. packages — paket harga per designer (Basic/Standard/Premium)
-- ---------------------------------------------------------------------
CREATE TABLE packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    designer_id INT NOT NULL,
    category_id INT NOT NULL,
    nama_paket ENUM('Basic','Standard','Premium') NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    jumlah_revisi INT DEFAULT 1,               -- -1 = unlimited
    estimasi_hari INT DEFAULT 3,
    file_ai TINYINT(1) DEFAULT 0,
    file_png TINYINT(1) DEFAULT 1,
    file_jpg TINYINT(1) DEFAULT 1,
    file_psd TINYINT(1) DEFAULT 0,
    brand_guideline TINYINT(1) DEFAULT 0,
    deskripsi TEXT DEFAULT NULL,
    FOREIGN KEY (designer_id) REFERENCES designers(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 6. orders — pesanan utama (header)
-- ---------------------------------------------------------------------
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_order VARCHAR(30) NOT NULL UNIQUE,     -- mis: KV-20260708-0001
    user_id INT NOT NULL,                       -- customer
    designer_id INT NOT NULL,
    package_id INT NOT NULL,
    total_harga DECIMAL(12,2) NOT NULL,
    status ENUM(
        'menunggu_pembayaran',
        'dibayar',
        'brief_masuk',
        'proses_desain',
        'revisi',
        'menunggu_approval',
        'selesai',
        'dibatalkan'
    ) NOT NULL DEFAULT 'menunggu_pembayaran',
    progress_percent TINYINT DEFAULT 0,
    deadline DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (designer_id) REFERENCES designers(id),
    FOREIGN KEY (package_id) REFERENCES packages(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 7. order_detail — brief, referensi, revisi, file hasil (baris per event)
-- ---------------------------------------------------------------------
CREATE TABLE order_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    tipe ENUM('brief','referensi','hasil_desain','revisi_request','catatan') NOT NULL,
    deskripsi TEXT DEFAULT NULL,
    file_path VARCHAR(255) DEFAULT NULL,
    created_by INT NOT NULL,                    -- users.id (siapa yang upload)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 8. payments — transaksi pembayaran
-- ---------------------------------------------------------------------
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    metode ENUM('transfer_bank','e_wallet','virtual_account','kartu_kredit') NOT NULL,
    jumlah DECIMAL(12,2) NOT NULL,
    kode_referensi VARCHAR(100) DEFAULT NULL,
    status ENUM('pending','sukses','gagal','refund') DEFAULT 'pending',
    bukti_bayar VARCHAR(255) DEFAULT NULL,
    paid_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 9. reviews — rating & ulasan customer terhadap designer
-- ---------------------------------------------------------------------
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL UNIQUE,
    user_id INT NOT NULL,
    designer_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    komentar TEXT DEFAULT NULL,
    status ENUM('visible','hidden') DEFAULT 'visible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (designer_id) REFERENCES designers(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 10. messages — chat antara customer & designer per order
-- ---------------------------------------------------------------------
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT DEFAULT NULL,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    pesan TEXT DEFAULT NULL,
    lampiran VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 11. notifications — notifikasi in-app
-- ---------------------------------------------------------------------
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    judul VARCHAR(150) NOT NULL,
    pesan VARCHAR(255) DEFAULT NULL,
    tipe ENUM('order','pembayaran','chat','sistem','verifikasi') DEFAULT 'sistem',
    link VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel pendukung tambahan (untuk fitur wishlist/favorite & blog di brief)
-- ---------------------------------------------------------------------
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    designer_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favorite (user_id, designer_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (designer_id) REFERENCES designers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    judul VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    thumbnail VARCHAR(255) DEFAULT NULL,
    konten LONGTEXT NOT NULL,
    status ENUM('draft','published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- =====================================================================
-- SEED DATA
-- =====================================================================

INSERT INTO categories (nama, slug, icon, deskripsi) VALUES
('Logo', 'logo', 'bi-vector-pen', 'Desain logo profesional untuk brand kamu'),
('Poster', 'poster', 'bi-image', 'Poster promosi, event, hingga campaign'),
('Brand Identity', 'brand-identity', 'bi-palette', 'Paket identitas visual lengkap'),
('Packaging', 'packaging', 'bi-box-seam', 'Desain kemasan produk'),
('Social Media', 'social-media', 'bi-instagram', 'Konten visual untuk media sosial');

-- Admin default (password: admin123 — HARUS diganti setelah instalasi)
INSERT INTO users (nama, email, password, role, status, email_verified_at) VALUES
('Admin DesainHub', 'admin@desainhub.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW());

-- Contoh designer
INSERT INTO users (nama, email, password, role, status, email_verified_at) VALUES
('Rian Pratama', 'rian@desainhub.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'designer', 'active', NOW());

INSERT INTO designers (user_id, spesialisasi, bio, pengalaman_tahun, rating_rata, jumlah_project, verified, status) VALUES
(2, 'Logo & Brand Identity', 'Designer berpengalaman 5 tahun membantu UMKM membangun identitas visual.', 5, 4.9, 128, 1, 'approved');

-- Contoh customer
INSERT INTO users (nama, email, password, role, status, email_verified_at) VALUES
('Dewi Anggraini', 'dewi@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'active', NOW());

INSERT INTO packages (designer_id, category_id, nama_paket, harga, jumlah_revisi, estimasi_hari, file_ai, file_png, file_jpg, file_psd, brand_guideline, deskripsi) VALUES
(1, 1, 'Basic', 150000, 2, 3, 0, 1, 1, 0, 0, 'Cocok untuk kebutuhan logo sederhana'),
(1, 1, 'Standard', 350000, 5, 4, 1, 1, 1, 0, 0, 'Termasuk file AI & revisi lebih banyak'),
(1, 1, 'Premium', 650000, -1, 5, 1, 1, 1, 1, 1, 'Revisi unlimited + brand guideline lengkap');