<?php
// Logika untuk menentukan menu mana yang aktif
// Mengambil value 'page' dari URL, defaultnya 'home'
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<nav class="navbar">
    <div class="container">
        
        <a href="index.php?page=home" class="brand-logo">
           
        </a>

        <input type="checkbox" id="nav-toggle" class="nav-toggle-input">
        <label for="nav-toggle" class="nav-toggle-label">
            <span></span> </label>

        <ul class="nav-menu">
            
            <li>
                <a href="index.php?page=home" class="<?= $currentPage == 'home' ? 'active' : ''; ?>">
                    Beranda
                </a>
            </li>
            
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                <li>
                    <a href="index.php?page=admin&action=dashboard" class="nav-btn-admin">
                        Dashboard Admin
                    </a>
                </li>
                <li>
                    <a href="index.php?page=auth&action=logout">Logout</a>
                </li>

            <?php elseif(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'customer'): ?>
                <li>
                    <a href="index.php?page=cart&action=view" class="<?= $currentPage == 'cart' ? 'active' : ''; ?>">
                        Keranjang
                        <?php if(isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
                            <span class="badge-count"><?= $_SESSION['cart_count']; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=orders&action=history" class="<?= $currentPage == 'orders' ? 'active' : ''; ?>">
                        Pesanan Saya
                    </a>
                </li>
                <li class="user-greeting">
                    Halo, <?= htmlspecialchars($_SESSION['user']['username']); ?>
                </li>
                <li>
                    <a href="index.php?page=auth&action=logout" class="btn-logout">Logout</a>
                </li>

            <?php else: ?>
                <li>
                    <a href="index.php?page=auth&action=login" class="btn-login">
                        Masuk / Daftar
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </div>
</nav>