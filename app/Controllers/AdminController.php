<?php
require_once '../app/Models/ProductModel.php';
require_once '../app/Models/OrderModel.php';
require_once '../app/Models/UserModel.php'; // Opsional, untuk hitung total user

class AdminController {
    private $productModel;
    private $orderModel;
    private $userModel;

    public function __construct() {
        // 1. CEK KEAMANAN (Middleware)
        // Pastikan yang mengakses adalah ADMIN
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->userModel = new UserModel();
    }

    // ==========================================================
    // 1. DASHBOARD
    // ==========================================================
    public function dashboard() {
        // Mengambil data statistik untuk ditampilkan di kartu warna-warni
        
        // Hitung total produk (Ambil semua lalu hitung jumlah arraynya)
        $products = $this->productModel->getAllProducts();
        $totalProducts = count($products);

        // Hitung total order & pendapatan
        $orders = $this->orderModel->getOrders();
        $pendingOrders = 0;
        $totalIncome = 0;
        
        // Ambil 5 order terbaru
        $recentOrders = array_slice($orders, 0, 5);

        foreach($orders as $o) {
            if($o['status'] == 'Pending') $pendingOrders++;
            // Asumsi status 'Completed' atau 'Shipped' dianggap pendapatan masuk
            if($o['status'] != 'Cancelled') $totalIncome += $o['total_price'];
        }

        // Tampilkan View
        require_once '../app/Views/admin/dashboard.php';
    }

    // ==========================================================
    // 2. MANAJEMEN PRODUK (Read, Create, Update, Delete)
    // ==========================================================
    
    // A. Menampilkan Daftar Produk
    public function products() {
        $products = $this->productModel->getAllProducts();
        require_once '../app/Views/admin/product-list.php';
    }

    // B. Menampilkan Form Tambah Produk
    public function create_product() {
        // Load view form kosongan
        require_once '../app/Views/admin/product-form.php';
    }

    // C. Proses Simpan Produk Baru (Action Form)
    public function store_product() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $description = $_POST['description'];

            // PROSES UPLOAD GAMBAR
            $imageName = "";
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                // Folder tujuan (Relative dari public/index.php)
                $targetDir = "images/products/";
                
                // Buat nama unik agar tidak bentrok (time + nama asli)
                $fileName = time() . '_' . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                
                // Upload file
                if(move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)){
                    $imageName = $fileName;
                }
            }

            // Simpan ke Database
            if ($this->productModel->addProduct($name, $price, $description, $imageName, $stock)) {
                $_SESSION['success'] = "Produk berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambah produk.";
            }

            header("Location: index.php?page=admin&action=products");
            exit;
        }
    }

    // D. Menampilkan Form Edit Produk
    public function edit_product() {
        $id = $_GET['id'];
        // Ambil data produk lama untuk diisi di form
        $product = $this->productModel->getProductById($id);
        
        require_once '../app/Views/admin/product-form.php';
    }

    // E. Proses Update Produk
    public function update_product() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $description = $_POST['description'];

            // Logika Gambar: Apakah admin upload gambar baru?
            $imageName = null; // Default null artinya gambar tidak diganti

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "images/products/";
                $fileName = time() . '_' . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $fileName;

                if(move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)){
                    $imageName = $fileName;
                    // Opsional: Hapus gambar lama jika perlu, tapi untuk aman biarkan saja
                }
            }

            // Panggil Model Update
            if ($this->productModel->updateProduct($id, $name, $price, $description, $stock, $imageName)) {
                $_SESSION['success'] = "Produk berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal update produk.";
            }

            header("Location: index.php?page=admin&action=products");
            exit;
        }
    }

    // F. Hapus Produk
    public function delete_product() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $oldImage = $_POST['old_image'];

            // Hapus File Gambar dari folder (agar server tidak penuh sampah)
            if (!empty($oldImage) && file_exists("images/products/" . $oldImage)) {
                unlink("images/products/" . $oldImage);
            }

            // Hapus Data dari Database
            if ($this->productModel->deleteProduct($id)) {
                $_SESSION['success'] = "Produk berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus produk.";
            }

            header("Location: index.php?page=admin&action=products");
            exit;
        }
    }

    // ==========================================================
    // 3. MANAJEMEN ORDER
    // ==========================================================
    
    // A. Lihat Daftar Pesanan
    public function orders() {
        $orders = $this->orderModel->getOrders();
        require_once '../app/Views/admin/order-list.php'; // Pastikan file view ini ada/dibuat
    }

    // B. Detail Pesanan (Opsional, jika ingin melihat isi cart user tsb)
    public function order_detail() {
        $orderId = $_GET['id'];
        // Anda mungkin perlu menambah method getOrderItems($orderId) di OrderModel
        // Untuk tahap awal, kita redirect ke list saja atau buat view sederhana
        echo "Fitur Detail Order ID: $orderId (Sedang dalam pengembangan)";
        // header("Location: index.php?page=admin&action=orders");
    }

    // C. Update Status Order (Pending -> Shipped -> Completed)
    public function update_status() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];

            // Kita perlu menambah fungsi updateStatus di OrderModel nanti
            // Anggap saja fungsi ini ada:
            // $this->orderModel->updateStatus($orderId, $status);
            
            // Simulasi query manual jika di Model belum ada:
            $db = new Database();
            $query = "UPDATE orders SET status = '$status' WHERE id = '$orderId'";
            mysqli_query($db->conn, $query);

            $_SESSION['success'] = "Status pesanan #$orderId diubah menjadi $status";
            header("Location: index.php?page=admin&action=orders");
            exit;
        }
    }
}
?>