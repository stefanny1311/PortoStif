-- Database: toko_klontongan
-- Aplikasi Toko Kelontongan - Sistem Kasir + E-Commerce

CREATE DATABASE IF NOT EXISTS toko_klontongan;
USE toko_klontongan;

-- Tabel users (autentikasi)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'kasir') DEFAULT 'kasir',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    barcode VARCHAR(50) UNIQUE,
    name VARCHAR(200) NOT NULL,
    buy_price DECIMAL(15,2) DEFAULT 0,
    sell_price DECIMAL(15,2) DEFAULT 0,
    stock DECIMAL(10,2) DEFAULT 0,
    min_stock DECIMAL(10,2) DEFAULT 5,
    unit VARCHAR(20) DEFAULT 'pcs',
    expired_date DATE NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabel customers
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    max_debt_limit DECIMAL(15,2) DEFAULT 0,
    current_debt DECIMAL(15,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel transactions (header transaksi)
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_no VARCHAR(30) UNIQUE NOT NULL,
    user_id INT,
    customer_id INT NULL,
    total_amount DECIMAL(15,2) DEFAULT 0,
    discount DECIMAL(15,2) DEFAULT 0,
    grand_total DECIMAL(15,2) DEFAULT 0,
    payment_amount DECIMAL(15,2) DEFAULT 0,
    change_amount DECIMAL(15,2) DEFAULT 0,
    payment_method ENUM('cash', 'qris', 'transfer', 'kasbon') DEFAULT 'cash',
    payment_status ENUM('lunas', 'pending', 'dibayar_sebagian') DEFAULT 'lunas',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabel transaction_details
CREATE TABLE IF NOT EXISTS transaction_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT,
    product_id INT,
    qty DECIMAL(10,2) NOT NULL,
    buy_price DECIMAL(15,2) DEFAULT 0,
    sell_price DECIMAL(15,2) DEFAULT 0,
    subtotal DECIMAL(15,2) DEFAULT 0,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabel debt_payments (cicilan bon)
CREATE TABLE IF NOT EXISTS debt_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    transaction_id INT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_method ENUM('cash', 'transfer') DEFAULT 'cash',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Insert default users (admin & kasir)
INSERT INTO users (name, email, password, role) VALUES 
('Admin Toko', 'admin@toko.com', '$2y$10$XR0l7jA7CKw1qp9GEXCiEuRcrwaj8BBVxSPLPEZG9ke.Oh6K03hQi', 'admin'),
('Kasir Toko', 'kasir@toko.com', '$2y$10$PvF0NgI.h69aR9cJsxk/S.sYNQPuLPJJ90S/Qn6i99/IXFBmQFTky', 'kasir');

-- Insert sample categories
INSERT INTO categories (name, slug) VALUES
('Sembako', 'sembako'),
('Minuman', 'minuman'),
('Bumbu Dapur', 'bumbu-dapur'),
('Makanan Ringan', 'makanan-ringan'),
('Rokok', 'rokok'),
('Perlengkapan Mandi', 'perlengkapan-mandi'),
('Alat Tulis', 'alat-tulis'),
('Lainnya', 'lainnya');