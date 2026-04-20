<?php
// app/Models/UserModel.php
require_once __DIR__ . '/../Config/Database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    public function register($username, $email, $password, $phone, $address) {
        $username = mysqli_real_escape_string($this->conn, $username);
        $email = mysqli_real_escape_string($this->conn, $email);
        $phone = mysqli_real_escape_string($this->conn, $phone);
        $address = mysqli_real_escape_string($this->conn, $address);
        
        // Hash Password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer'; 

        $query = "INSERT INTO users (username, email, password, phone, address, role) 
                  VALUES ('$username', '$email', '$hashedPassword', '$phone', '$address', '$role')";
        
        return mysqli_query($this->conn, $query);
    }

    public function getByUsername($username) {
        $username = mysqli_real_escape_string($this->conn, $username);
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function verifyLogin($username, $inputPassword) {
        $user = $this->getByUsername($username);
        if ($user && password_verify($inputPassword, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function isUsernameTaken($username) {
        return $this->getByUsername($username) ? true : false;
    }
}
?>