<?php

class ProductModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    // ==========================================
    // BAGIAN CUSTOMER (READ ONLY)
    // ==========================================

    // 1. Mengambil SEMUA produk (untuk halaman Katalog/Shop)
    public function getAllProducts() {
        $query = "SELECT * FROM products ORDER BY id DESC"; // Produk terbaru di atas
        $result = mysqli_query($this->conn, $query);
        
        $products = [];
        while($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }

    // 2. Mengambil detail SATU produk (untuk halaman Detail Produk)
    public function getProductById($id) {
        // Mencegah SQL Injection sederhana
        $id = mysqli_real_escape_string($this->conn, $id);
        
        $query = "SELECT * FROM products WHERE id = '$id'";
        $result = mysqli_query($this->conn, $query);
        
        return mysqli_fetch_assoc($result); // Mengembalikan 1 baris data saja
    }

    // 3. Mengambil beberapa produk terbaru (untuk Halaman Home/Banner)
    public function getLatestProducts($limit = 4) {
        $query = "SELECT * FROM products ORDER BY id DESC LIMIT $limit";
        $result = mysqli_query($this->conn, $query);
        
        $products = [];
        while($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }

    // ==========================================
    // BAGIAN ADMIN (CREATE, UPDATE, DELETE)
    // ==========================================

    // 4. Menambah Produk Baru
    public function addProduct($name, $price, $description, $image, $stock) {
        // Sanitasi input agar aman
        $name = mysqli_real_escape_string($this->conn, $name);
        $description = mysqli_real_escape_string($this->conn, $description);
        $image = mysqli_real_escape_string($this->conn, $image);
        
        $query = "INSERT INTO products (name, price, description, image, stock) 
                  VALUES ('$name', '$price', '$description', '$image', '$stock')";
        
        return mysqli_query($this->conn, $query);
    }

    // 5. Mengupdate Produk
    // Parameter $image opsional, jika null berarti gambar tidak diganti
    public function updateProduct($id, $name, $price, $description, $stock, $image = null) {
        $name = mysqli_real_escape_string($this->conn, $name);
        $description = mysqli_real_escape_string($this->conn, $description);
        
        if ($image != null) {
            // Jika ada gambar baru
            $image = mysqli_real_escape_string($this->conn, $image);
            $query = "UPDATE products SET 
                      name='$name', price='$price', description='$description', stock='$stock', image='$image' 
                      WHERE id='$id'";
        } else {
            // Jika gambar tidak diganti, jangan update kolom image
            $query = "UPDATE products SET 
                      name='$name', price='$price', description='$description', stock='$stock' 
                      WHERE id='$id'";
        }

        return mysqli_query($this->conn, $query);
    }

    // 6. Menghapus Produk
    public function deleteProduct($id) {
        $id = mysqli_real_escape_string($this->conn, $id);
        $query = "DELETE FROM products WHERE id = '$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>