<?php 
// app/Views/shop/home.php
// Variabel $baseUrl diambil dari controller atau global
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$baseUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
$baseUrl = str_replace('/index.php/', '/', $baseUrl);
?>

<!-- HERO SECTION SPESIAL HOME -->
<section class="hero-section home-hero">
    <div class="container hero-content">
        
        <!-- 1. LOGO CENTER (UKURAN DESKTOP MENYESUAIKAN) -->
        <div class="hero-logo-container">
            <img src="<?= $baseUrl; ?>img/logo.png" alt="Ecofashion Logo" class="hero-logo">
            <h1>Fashion Ramah Lingkungan <br> Untuk Masa Depan</h1>
            <h2 class="brand-text">Welcome to Eco Fashion Shop</h2>
        </div>

        <!-- 2. JUDUL & DESKRIPSI -->
        
        <!-- 3. TOMBOL BELANJA -->
        <div class="hero-actions">
            <a href="#katalog-produk" class="btn-primary btn-lg">Belanja Sekarang</a>
        </div>

        <!-- 4. NAVIGATION LINKS (DI BAWAH TOMBOL) -->
        <nav class="navigation-links">
            <a href="index.php?page=product">Katalog</a>
            <a href="index.php?page=home#features">Keunggulan</a>
            <a href="index.php?page=page&action=about">Tentang Kami</a>
            <a href="index.php?page=page&action=contact">Kontak</a>

             <script>
                if(window.location.search === '' || window.location.search.includes('page=home')) {
                    const intro = document.getElementById('intro-screen');
                if(intro && !sessionStorage.getItem('introShown')) {
                    intro.style.display = 'flex';
                    setTimeout(() => {
                        intro.style.opacity = '0';
                        setTimeout(() => { intro.style.display = 'none'; }, 1000);
                    }, 2000); 
                    sessionStorage.setItem('introShown', 'true');
                    }
                }
            </script>
            
            <!-- Login/Cart juga ditaruh sini agar mudah diakses -->
            <?php if(isset($_SESSION['user'])): ?>
                <a href="<?= $baseUrl; ?>index.php?page=cart&action=view" class="nav-special">
                    <i class="fas fa-shopping-cart"></i> Cart (<?= $_SESSION['cart_count'] ?? 0; ?>)
                </a>
                <a href="<?= $baseUrl; ?>index.php?page=auth&action=logout" class="nav-special">Logout</a>
            <?php else: ?>
                <a href="<?= $baseUrl; ?>index.php?page=auth&action=login" class="nav-special">Login</a>
            <?php endif; ?>
        </nav>

    </div>
</section>

<div class="container main-content">

    <!-- Flash Message -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <section class="dashboard-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h2>Discover Sustainable Fashion</h2>
                <p>Explore our collection of eco-friendly clothing and accessories that combine style with environmental consciousness.</p>
                <div class="welcome-highlights">
                    <span class="highlight-badge">✓ 100% Organic</span>
                    <span class="highlight-badge">✓ Carbon Neutral</span>
                    <span class="highlight-badge">✓ Fair Trade</span>
                </div>
            </div>
            <div class="welcome-image">
                <img src="../public/img/logo.png"  />
            </div>
        </div>


    <section class="features-grid" id="features">
        <div class="feature-item">
            <div class="icon">🌿</div>
            <h3>100% Organik</h3>
            <p>Bahan katun alami tanpa pestisida berbahaya.</p>
        </div>
        <div class="feature-item">
            <div class="icon">🇮🇩</div>
            <h3>Karya Lokal</h3>
            <p>Dibuat oleh pengrajin lokal Indonesia.</p>
        </div>
        <div class="feature-item">
            <div class="icon">♻️</div>
            <h3>Eco Packaging</h3>
            <p>Kemasan bebas plastik yang mudah terurai.</p>
        </div>
    </section>

    <hr class="divider">

    <section id="katalog-produk" class="latest-products">
        <div class="section-header center">
            <h2>Koleksi Terbaru</h2>
            <p>Pilihan terbaik minggu ini untuk Anda</p>
        </div>

        <div class="product-grid">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach($featuredProducts as $p): ?>
                    <div class="product-card">
                        <a href="index.php?page=product&action=detail&id=<?= $p['id']; ?>" class="card-img-link">
                            <?php if(!empty($p['image'])): ?>
                                <img src="images/products/<?= $p['image']; ?>" alt="<?= $p['name']; ?>">
                            <?php else: ?>
                                <img src="images/ui/no-image.png" alt="No Image">
                            <?php endif; ?>
                        </a>
                        <div class="card-body">
                            <h3 class="product-title">
                                <a href="index.php?page=product&action=detail&id=<?= $p['id']; ?>">
                                    <?= $p['name']; ?>
                                </a>
                            </h3>
                            <p class="product-price">
                                Rp <?= number_format($p['price'], 0, ',', '.'); ?>
                            </p>
                            <div class="card-actions">
                                <a href="index.php?page=product&action=detail&id=<?= $p['id']; ?>" class="btn-secondary btn-sm">Detail</a>
                                <?php if($p['stock'] > 0): ?>
                                    <form action="index.php?page=cart&action=add" method="POST" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?= $p['id']; ?>">
                                        <input type="hidden" name="quantity" value="1"> 
                                        <?php if(isset($_SESSION['user'])): ?>
                                            <button type="submit" class="btn-primary btn-sm">Beli</button>
                                        <?php endif; ?>
                                    </form>
                                <?php else: ?>
                                    <span class="badge-habis">Habis</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center; width:100%;">Belum ada produk yang ditampilkan.</p>
            <?php endif; ?>
        </div>

        <div class="center-btn" style="margin-top: 30px; text-align: center;">
            <a href="index.php?page=product&action=index" class="btn-text">Lihat Semua Katalog &rarr;</a>
        </div>
    </section>
</div>