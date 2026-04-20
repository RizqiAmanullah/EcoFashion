<?php
// app/Controllers/PageController.php

// PERBAIKAN: Gunakan __DIR__ agar jalur file menjadi mutlak (Pasti Ketemu)
// Artinya: Dari folder Controllers ini, mundur satu langkah (../), lalu masuk Models
require_once __DIR__ . '/../Models/ProductModel.php';

class PageController {

    // Method ini dipanggil saat user membuka halaman utama (Root URL)
    public function index() {
        $title = "Home - Ecofashion";
        
        // Mengambil data produk
        $productModel = new ProductModel();
        $featuredProducts = $productModel->getAllProducts(); 
        
        // Gunakan __DIR__ juga untuk memanggil View agar lebih aman
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/shop/home.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    // Halaman About
    public function about() {
        $title = "Tentang Kami - Ecofashion";

        require_once __DIR__ . '/../Views/layouts/header.php';
        
        // Cek keberadaan file menggunakan jalur mutlak
        if (file_exists(__DIR__ . '/../Views/shop/about.php')) {
            require_once __DIR__ . '/../Views/shop/about.php';
        } else {
            // Tampilan Fallback jika file belum dibuat
            echo "<div class='container main-content' style='text-align:center; padding: 50px;'>";
            echo "<h2>Tentang Kami</h2>";
            echo "<p>Halaman ini sedang dalam tahap pengembangan.</p>";
            echo "</div>";
        }
        
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    // Halaman Contact
    public function contact() {
        $title = "Hubungi Kami - Ecofashion";

        require_once __DIR__ . '/../Views/layouts/header.php';
        
        if (file_exists(__DIR__ . '/../Views/shop/contact.php')) {
            require_once __DIR__ . '/../Views/shop/contact.php';
        } else {
            echo "<div class='container main-content' style='text-align:center; padding: 50px;'>";
            echo "<h2>Hubungi Kami</h2>";
            echo "<p>Silakan hubungi kami via WhatsApp: 0812-3456-7890</p>";
            echo "</div>";
        }
        
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }
}
?>