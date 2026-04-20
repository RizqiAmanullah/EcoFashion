<?php
require_once '../app/Models/ProductModel.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    // Halaman Katalog Utama (Semua Produk)
    public function index() {
        $products = $this->ProductModel->getAllProducts();
        require_once '../app/Views/shop/product-list.php'; // Pastikan view ini ada
    }

    // Halaman Detail Produk
    public function detail() {
        // Ambil ID dari URL (index.php?page=product&action=detail&id=1)
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $product = $this->productModel->getProductById($id);

            if ($product) {
                require_once '../app/Views/shop/detail.php';
            } else {
                echo "Produk tidak ditemukan.";
            }
        } else {
            header("Location: index.php?page=home");
        }
    }
}
?>