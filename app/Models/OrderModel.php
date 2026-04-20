<?php
// app/Models/OrderModel.php
require_once '../app/Config/Database.php';

class OrderModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    // =========================================================
    // BAGIAN KERANJANG (CART)
    // =========================================================

    // 1. Menampilkan isi keranjang berdasarkan User yang login
    public function getCartByUser($userId) {
        // Kita gunakan JOIN agar bisa sekaligus mengambil Nama, Harga, dan Gambar produk
        $query = "SELECT cart.id as cart_id, cart.quantity, products.name, products.price, products.image, products.id as product_id
                  FROM cart 
                  JOIN products ON cart.product_id = products.id 
                  WHERE cart.user_id = '$userId'";
        
        $result = mysqli_query($this->conn, $query);
        
        $items = [];
        while($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    // 2. Menambah barang ke keranjang
    public function addToCart($userId, $productId, $quantity) {
        // Cek dulu apakah barang sudah ada di keranjang user tersebut?
        $checkQuery = "SELECT * FROM cart WHERE user_id = '$userId' AND product_id = '$productId'";
        $checkResult = mysqli_query($this->conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Jika sudah ada, kita update jumlahnya saja (tambah quantity)
            $query = "UPDATE cart SET quantity = quantity + $quantity 
                      WHERE user_id = '$userId' AND product_id = '$productId'";
        } else {
            // Jika belum ada, masukkan data baru
            $query = "INSERT INTO cart (user_id, product_id, quantity) 
                      VALUES ('$userId', '$productId', '$quantity')";
        }

        return mysqli_query($this->conn, $query);
    }

    // 3. Menghapus satu item dari keranjang
    public function removeFromCart($cartId) {
        // Sanitasi ID
        $cartId = mysqli_real_escape_string($this->conn, $cartId);
        $query = "DELETE FROM cart WHERE id = '$cartId'";
        return mysqli_query($this->conn, $query);
    }

    // [BARU] 4. Update Jumlah Barang (Tombol Refresh di Keranjang)
    public function updateQuantity($cartId, $quantity) {
        $cartId = mysqli_real_escape_string($this->conn, $cartId);
        $quantity = (int)$quantity; // Pastikan angka integer
        
        $query = "UPDATE cart SET quantity = $quantity WHERE id = '$cartId'";
        return mysqli_query($this->conn, $query);
    }

    // =========================================================
    // BAGIAN CHECKOUT / PEMBAYARAN
    // =========================================================

    // 5. Membuat Pesanan Baru (Pindah dari Cart ke Tabel Orders)
    public function createOrder($userId, $address, $paymentMethod, $totalPrice) {
        // Sanitasi input
        $userId = mysqli_real_escape_string($this->conn, $userId);
        $address = mysqli_real_escape_string($this->conn, $address);
        $paymentMethod = mysqli_real_escape_string($this->conn, $paymentMethod);
        
        $date = date('Y-m-d H:i:s');
        $status = 'Pending'; // Status awal

        // Query Insert ke tabel orders
        $query = "INSERT INTO orders (user_id, address, payment_method, total_price, order_date, status) 
                  VALUES ('$userId', '$address', '$paymentMethod', '$totalPrice', '$date', '$status')";
        
        if (mysqli_query($this->conn, $query)) {
            // Jika berhasil simpan order, KOSONGKAN KERANJANG user tersebut
            $this->clearCart($userId);
            return true;
        } else {
            return false;
        }
    }

    // 6. Mengosongkan keranjang setelah checkout berhasil
    private function clearCart($userId) {
        $userId = mysqli_real_escape_string($this->conn, $userId);
        $query = "DELETE FROM cart WHERE user_id = '$userId'";
        mysqli_query($this->conn, $query);
    }

    // 7. Melihat Riwayat Pesanan (Untuk Customer atau Admin)
    public function getOrders($userId = null) {
        $query = "SELECT * FROM orders";
        
        // Jika userId diisi, ambil order milik user itu saja. Jika null, ambil semua (untuk admin)
        if ($userId != null) {
            $userId = mysqli_real_escape_string($this->conn, $userId);
            $query .= " WHERE user_id = '$userId'";
        }
        
        $query .= " ORDER BY order_date DESC"; // Urutkan dari yang terbaru

        $result = mysqli_query($this->conn, $query);
        $orders = [];
        while($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        return $orders;
    }

    // [BARU] 8. Update Status Order (Fitur Admin: Pending -> Shipped)
    public function updateOrderStatus($orderId, $status) {
        $orderId = mysqli_real_escape_string($this->conn, $orderId);
        $status = mysqli_real_escape_string($this->conn, $status);
        
        $query = "UPDATE orders SET status = '$status' WHERE id = '$orderId'";
        return mysqli_query($this->conn, $query);
    }
}
?>