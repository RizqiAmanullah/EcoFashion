<?php include '../app/Views/layouts/header.php'; ?>
<?php include '../app/Views/layouts/navbar.php'; ?>

<div class="container main-content">
    
    <div class="section-header center">
        <h2>Semua Produk</h2>
        <p>Temukan koleksi fashion ramah lingkungan terbaik kami</p>
    </div>

    <!-- Grid Produk (Menggunakan Style yang sama dengan Home) -->
    <div class="product-grid">
        <?php if (!empty($products)): ?>
            <?php foreach($products as $p): ?>
                
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
                            <a href="index.php?page=product&action=detail&id=<?= $p['id']; ?>" class="btn-secondary btn-sm">
                                Detail
                            </a>

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
            <div class="empty-state" style="width: 100%; text-align: center; padding: 50px;">
                <h3>Belum ada produk</h3>
                <p>Admin belum menambahkan produk apapun.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include '../app/Views/layouts/footer.php'; ?>