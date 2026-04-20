<?php
class Database {
    // Sesuaikan konfigurasi ini dengan XAMPP/Server Anda
    private $host = "localhost";
    private $user = "root";
    private $pass = ""; 
    private $db_name = "ecofashion"; // Pastikan nama database di PHPMyAdmin sama dengan ini
    
    public $conn;

    public function __construct() {
        // Membuat koneksi menggunakan MySQLi
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db_name);

        // Cek jika koneksi gagal
        if (!$this->conn) {
            // Die akan menghentikan program dan menampilkan pesan error
            die("Koneksi Database Gagal: " . mysqli_connect_error());
        }
    }
}
?>