<?php
// app/Views/layouts/header.php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['PHP_SELF']);
$baseUrl = $protocol . "://" . $host . $path;
$baseUrl = str_replace('\\', '/', $baseUrl);
$baseUrl = str_replace('/index.php', '', $baseUrl);
$baseUrl = rtrim($baseUrl, '/') . '/';

// Cek apakah ini halaman Home? (Untuk Intro Screen saja)
$isHome = (!isset($_GET['page']) || $_GET['page'] == 'home');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Ecofashion'; ?></title>
    
    <link rel="stylesheet" href="<?= $baseUrl; ?>css/style.css?v=<?= time(); ?>">
    
    <?php if(isset($_GET['page']) && $_GET['page'] == 'admin'): ?>
        <link rel="stylesheet" href="<?= $baseUrl; ?>css/admin.css?v=<?= time(); ?>">
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- INTRO SCREEN (Hanya di Home) -->
    <?php if($isHome): ?>
    <div class="logo-fullscreen" id="intro-screen" style="display:none;"> 
        <div class="logo-container">
            <img src="<?= $baseUrl; ?>img/logo.png" onerror="this.style.display='none'">
            <h1 style="color:white; font-size:3rem;">ECOFASHION</h1>
        </div>
    </div>
    <?php endif; ?>

    <!-- MAIN HEADER (SELALU TAMPIL) -->
    <header class="main-header">
        <!-- Logo Kiri -->
        <div class="header-logo">
            <a href="<?= $baseUrl; ?>" style="text-decoration:none; color:white; display:flex; align-items:center;">
                <img src="<?= $baseUrl; ?>img/logo.png" style="height: 50px; margin-right: 10px; object-fit: contain; border-radius: 5px;">
                <span style="font-weight:bold; font-size:1.5rem; letter-spacing:1px;"></span>
            </a>
        </div>

        <!-- Search Bar Tengah -->
        <div class="header-search">
            <form action="<?= $baseUrl; ?>index.php" method="GET" style="display:flex; width:100%;">
                <input type="hidden" name="page" value="product">
                <input type="text" id="search-bar" name="q" placeholder="Cari produk ramah lingkungan..." required>
                <button type="submit" id="search-btn"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <!-- User Info Kanan -->
        <div class="header-user-info">
            
            <!-- TOMBOL CART (Muncul untuk Guest & Customer) -->
            <?php if(!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']['role'] != 'admin')): ?>
                <a href="<?= $baseUrl; ?>index.php?page=cart&action=view">
                    <button id="cart-btn">
                        <i class="fas fa-shopping-cart"></i> 
                        Cart (<span id="cart-count"><?= $_SESSION['cart_count'] ?? 0; ?></span>)
                    </button>
                </a>
            <?php endif; ?>

            <?php if(isset($_SESSION['user'])): ?>
                
                <span id="user-greeting" style="color:white; margin: 0 10px;">
                    Hi, <?= htmlspecialchars($_SESSION['user']['username']); ?>
                </span>
                
                <?php if($_SESSION['user']['role'] == 'admin'): ?>
                    <a href="<?= $baseUrl; ?>index.php?page=admin&action=dashboard">
                        <button id="account-btn"><i class="fas fa-tachometer-alt"></i> Dashboard</button>
                    </a>
                <?php endif; ?>
                
                <a href="<?= $baseUrl; ?>index.php?page=auth&action=logout" style="margin-left: 5px;">
                    <button style="background:transparent; border:1px solid white; padding: 5px 10px; box-shadow:none;">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </a>

            <?php else: ?>
                
                <a href="<?= $baseUrl; ?>index.php?page=auth&action=login" style="margin-left: 10px;">
                    <button id="account-btn"><i class="fas fa-user"></i> Login</button>
                </a>
            <?php endif; ?>
        </div>
    </header>
   