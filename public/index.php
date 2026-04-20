<?php

// -----------------------------
// 1. Mulai Session (Wajib paling atas untuk Login/Keranjang)
session_start();

// 2. Panggil Konfigurasi Database
require_once '../app/Config/Database.php';

// 3. Ambil Parameter URL (Routing)
// Contoh URL: index.php?page=auth&action=login
// Jika tidak ada 'page', default ke 'home'. Jika tidak ada 'action', default ke 'index'.
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Variabel untuk menyimpan Controller yang akan dipakai
$controller = null;

// 4. Logika Pemilihan Controller (Switch Case)
switch ($page) {
    
    // A. Area Otentikasi (Login/Register/Logout)
    case 'auth':
        require_once '../app/Controllers/AuthController.php';
        $controller = new AuthController();
        break;

    // B. Area Admin (Dashboard, Kelola Produk, Order)
    case 'admin':
        // Cek Keamanan: Cegah user biasa masuk ke admin via URL
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header("Location: index.php?page=auth&action=login");
            exit;
        }
        require_once '../app/Controllers/AdminController.php';
        $controller = new AdminController();
        break;

    // C. Area Belanja (Keranjang & Checkout)
    case 'cart':
    case 'orders': // Digabung ke OrderController
        require_once '../app/Controllers/OrderController.php';
        $controller = new OrderController();
        break;

    // D. Area Produk (Detail Produk, List Produk)
    case 'product':
        require_once '../app/Controllers/ProductController.php';
        $controller = new ProductController();
        break;

    // E. Halaman Statis (Home, About, Contact)
    case 'home':
    default:
        // Jika halaman tidak dikenali, lari ke Home (PageController)
        require_once '../app/Controllers/PageController.php';
        $controller = new PageController();
        break;
}

// 5. Eksekusi Fungsi (Method) di dalam Controller
if ($controller && method_exists($controller, $action)) {
    // Panggil fungsi sesuai nama action (contoh: login(), dashboard(), add())
    $controller->$action();
} else {
    // Jika fungsi tidak ditemukan, tampilkan pesan error sederhana
    echo "<h1>404 Not Found</h1>";
    echo "<p>Halaman atau aksi tidak ditemukan.</p>";
    echo "<a href='index.php'>Kembali ke Beranda</a>";
}
?>