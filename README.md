# 🌿 EcoFashion

**EcoFashion** adalah platform e-commerce fashion ramah lingkungan berbasis PHP Native yang menjual produk pakaian dan aksesoris berkelanjutan. Dibangun dengan arsitektur MVC sederhana tanpa framework.

---

## 📋 Daftar Isi

- [Fitur](#-fitur)
- [Tech Stack](#-tech-stack)
- [Struktur Folder](#-struktur-folder)
- [Prasyarat](#-prasyarat)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Penggunaan](#-penggunaan)
- [Akun Default](#-akun-default)
- [Screenshot](#-screenshot)
- [Kontributor](#-kontributor)
- [Lisensi](#-lisensi)

---

## ✨ Fitur

### 🛒 Customer
- Registrasi & Login akun
- Melihat katalog produk & detail produk
- Menambahkan produk ke keranjang belanja
- Checkout & pemesanan dengan pilihan metode pembayaran
- Riwayat pesanan

### 🔧 Admin
- Dashboard statistik (total produk, pesanan, pendapatan)
- Manajemen produk (CRUD: Tambah, Edit, Hapus)
- Upload gambar produk
- Manajemen pesanan & update status order (Pending → Shipped → Completed)
- Daftar 5 pesanan terbaru di dashboard

### 🌱 Keunggulan Produk
- 100% Bahan Organik
- Karya Pengrajin Lokal Indonesia
- Eco Packaging (bebas plastik)
- Carbon Neutral & Fair Trade

---

## 🛠 Tech Stack

| Teknologi   | Keterangan                          |
|-------------|-------------------------------------|
| **PHP**     | Backend (Native PHP, tanpa framework) |
| **MySQL**   | Database relasional                 |
| **MySQLi**  | Driver koneksi database             |
| **HTML/CSS**| Frontend & styling                  |
| **JavaScript** | Interaksi dinamis (intro screen, dll) |
| **Font Awesome** | Ikon UI                        |

---

## 📁 Struktur Folder

```
ecofashion2/
├── app/
│   ├── Config/
│   │   ├── Database.php          # Konfigurasi koneksi database
│   │   └── database.sql          # Script SQL untuk membuat tabel
│   ├── Controllers/
│   │   ├── AdminController.php   # Controller area admin
│   │   ├── AuthController.php    # Controller login/register/logout
│   │   ├── OrderController.php   # Controller keranjang & checkout
│   │   ├── PageController.php    # Controller halaman statis (home, about, contact)
│   │   └── ProductController.php # Controller katalog & detail produk
│   ├── Models/
│   │   ├── OrderModel.php        # Model pesanan & keranjang
│   │   ├── ProductModel.php      # Model produk (CRUD)
│   │   └── UserModel.php         # Model user (auth)
│   └── Views/
│       ├── admin/
│       │   ├── dashboard.php     # Halaman dashboard admin
│       │   ├── order-list.php    # Daftar pesanan admin
│       │   ├── product-form.php  # Form tambah/edit produk
│       │   └── product-list.php  # Daftar produk admin
│       ├── auth/
│       │   ├── login.php         # Halaman login
│       │   └── register.php      # Halaman registrasi
│       ├── layouts/
│       │   ├── footer.php        # Template footer
│       │   ├── header.php        # Template header
│       │   ├── navbar.php        # Template navigasi
│       │   └── sidebar.php       # Template sidebar admin
│       └── shop/
│           ├── cart.php          # Halaman keranjang belanja
│           ├── detail.php        # Halaman detail produk
│           ├── home.php          # Halaman utama / beranda
│           └── product-list.php  # Halaman katalog produk
├── public/
│   ├── css/
│   │   ├── admin.css             # Styling area admin
│   │   └── style.css             # Styling utama website
│   ├── img/
│   │   └── logo.png              # Logo EcoFashion
│   └── index.php                 # Entry point & routing aplikasi
└── README.md
```

---

## 📌 Prasyarat

Pastikan perangkat Anda sudah terinstal:

- **PHP** >= 7.4
- **MySQL** >= 5.7
- **Web Server** (Apache/Nginx) — disarankan menggunakan **XAMPP** atau **Laragon**
- **Browser** modern (Chrome, Firefox, Edge)

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/RizqiAmanullah/EcoFashion.git
cd EcoFashion
```

### 2. Pindahkan ke Web Server

Letakkan folder project ke dalam direktori root web server Anda:

- **XAMPP**: `C:\xampp\htdocs\ecofashion2`
- **Laragon**: `C:\laragon\www\ecofashion2`

### 3. Buat Database

1. Buka **phpMyAdmin** di browser (`http://localhost/phpmyadmin`)
2. Buat database baru dengan nama `ecofashion`
3. Import file SQL:
   - Klik database `ecofashion`
   - Pilih tab **Import**
   - Pilih file `app/Config/database.sql`
   - Klik **Go / Kirim**

Atau jalankan via terminal MySQL:

```bash
mysql -u root -p < app/Config/database.sql
```

### 4. Buat Folder Upload Gambar

```bash
mkdir -p public/images/products
```

Pastikan folder tersebut memiliki izin tulis (writable).

---

## ⚙ Konfigurasi

Edit file `app/Config/Database.php` sesuai konfigurasi server Anda:

```php
private $host = "localhost";
private $user = "root";
private $pass = "";            // Isi jika ada password MySQL
private $db_name = "ecofashion";
```

---

## 💻 Penggunaan

### Akses Website

Buka browser dan kunjungi:

```
http://localhost/ecofashion2/public/
```

> **Catatan:** Sesuaikan URL dengan nama folder dan konfigurasi web server Anda.

### Navigasi URL

Aplikasi ini menggunakan routing berbasis query string:

| URL                                          | Keterangan              |
|----------------------------------------------|-------------------------|
| `index.php`                                  | Halaman Beranda         |
| `index.php?page=product`                     | Katalog Produk          |
| `index.php?page=product&action=detail&id=1`  | Detail Produk           |
| `index.php?page=auth&action=login`           | Halaman Login           |
| `index.php?page=auth&action=register`        | Halaman Registrasi      |
| `index.php?page=cart&action=view`            | Keranjang Belanja       |
| `index.php?page=admin&action=dashboard`      | Dashboard Admin         |
| `index.php?page=admin&action=products`       | Kelola Produk (Admin)   |
| `index.php?page=admin&action=orders`         | Kelola Pesanan (Admin)  |

---

## 🔑 Akun Default

Setelah import `database.sql`, tersedia akun admin default:

| Field    | Nilai                  |
|----------|------------------------|
| Username | `admin`                |
| Email    | `admin@ecofashion.com` |
| Password | `admin123`             |
| Role     | `admin`                |

> ⚠️ **Catatan:** Jika password hash default tidak bekerja, silakan daftar akun baru via halaman Register, lalu ubah `role` menjadi `admin` secara manual melalui phpMyAdmin.

---

## 📸 Screenshot

> *Screenshot akan ditambahkan segera.*

---

## 👥 Kontributor

- **Rizqi Amanullah** — Developer

---

## 📄 Lisensi

Project ini dibuat untuk keperluan akademik (Tugas Kuliah Semester 5).

---


