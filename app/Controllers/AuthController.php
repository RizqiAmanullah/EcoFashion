<?php
// app/Controllers/AuthController.php

// Pastikan path ke Model benar menggunakan __DIR__
require_once __DIR__ . '/../Models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        // Pastikan class UserModel sudah ada
        if (class_exists('UserModel')) {
            $this->userModel = new UserModel();
        } else {
            die("Error: Class UserModel belum ada. Cek file app/Models/UserModel.php");
        }
    }

    // ==========================================================
    // 1. LOGIN
    // ==========================================================
    public function login() {
        // Jika sudah login, redirect sesuai role
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 'admin') {
                header("Location: index.php?page=admin&action=dashboard");
            } else {
                header("Location: index.php?page=home");
            }
            exit;
        }

        // Tampilkan View Login
        // Gunakan __DIR__ agar path view aman
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function process_login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->verifyLogin($username, $password);

            if ($user) {
                // Set Session
                $_SESSION['user'] = $user;

                // Redirect
                if ($user['role'] == 'admin') {
                    header("Location: index.php?page=admin&action=dashboard");
                } else {
                    header("Location: index.php?page=home");
                }
                exit;
            } else {
                $_SESSION['error'] = "Username atau Password salah!";
                header("Location: index.php?page=auth&action=login");
                exit;
            }
        }
    }

    // ==========================================================
    // 2. REGISTER
    // ==========================================================
    public function register() {
        if (isset($_SESSION['user'])) {
            header("Location: index.php?page=home");
            exit;
        }
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    public function process_register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email    = $_POST['email'];
            $password = $_POST['password'];
            $phone    = $_POST['phone'];
            $address  = $_POST['address'];

            if ($this->userModel->isUsernameTaken($username)) {
                $_SESSION['error'] = "Username sudah terdaftar.";
                header("Location: index.php?page=auth&action=register");
                exit;
            }

            if ($this->userModel->register($username, $email, $password, $phone, $address)) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header("Location: index.php?page=auth&action=login");
            } else {
                $_SESSION['error'] = "Gagal mendaftar. Coba lagi.";
                header("Location: index.php?page=auth&action=register");
            }
            exit;
        }
    }

    // ==========================================================
    // 3. LOGOUT
    // ==========================================================
    public function logout() {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['success'] = "Anda berhasil logout.";
        header("Location: index.php?page=auth&action=login");
        exit;
    }
}
?>