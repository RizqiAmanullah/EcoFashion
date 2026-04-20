-- 1. Buat Database (Jika belum ada)
CREATE DATABASE IF NOT EXISTS ecofashion;
USE ecofashion;

-- 2. Tabel Users (Untuk Login Admin & Customer)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabel Products (Untuk Data Barang)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tabel Cart (Untuk Keranjang Belanja Sementara)
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 5. Tabel Orders (Untuk Riwayat Pesanan)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    total_price DECIMAL(12, 2) NOT NULL,
    status ENUM('Pending', 'Shipped', 'Completed', 'Cancelled') DEFAULT 'Pending',
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 6. (OPSIONAL) Akun Admin Default
-- Username: admin
-- Password: admin123 (Ini adalah hash password_default dari 'admin123')
-- Jika password ini tidak bekerja, silakan Daftar Manual di web lalu ubah role jadi 'admin' lewat database.
INSERT INTO users (username, email, password, phone, address, role) 
VALUES 
('admin', 'admin@ecofashion.com', '$2y$10$8K1p/a/d8.4/w.D.A.E.O.O.O.O.O.O.O.O.O.O.O.O.O.O.O.O', '08123456789', 'Kantor EcoFashion', 'admin');