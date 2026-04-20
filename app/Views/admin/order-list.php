<?php include '../app/Views/layouts/header.php'; ?>

<div class="admin-wrapper">
    <?php include '../app/Views/layouts/sidebar-admin.php'; ?>

    <main class="admin-content">
        <div class="section-header">
            <h2>Daftar Pesanan Masuk</h2>
        </div>

        <!-- Flash Message -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>User ID</th>
                        <th>Total</th>
                        <th>Status Saat Ini</th>
                        <th>Aksi (Update Status)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($orders)): ?>
                        <?php foreach($orders as $o): ?>
                            <tr>
                                <td>#<?= $o['id']; ?></td>
                                <td><?= date('d M Y', strtotime($o['order_date'])); ?></td>
                                <td><?= $o['user_id']; ?></td>
                                <td>Rp <?= number_format($o['total_price'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge status-<?= strtolower($o['status']); ?>">
                                        <?= $o['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Form Update Status (Tanpa JS) -->
                                    <form action="index.php?page=admin&action=update_status" method="POST" style="display:flex; gap:5px;">
                                        <input type="hidden" name="order_id" value="<?= $o['id']; ?>">
                                        
                                        <select name="status" class="form-control" style="padding: 5px; font-size: 0.9rem;">
                                            <option value="Pending" <?= $o['status']=='Pending'?'selected':''; ?>>Pending</option>
                                            <option value="Shipped" <?= $o['status']=='Shipped'?'selected':''; ?>>Shipped</option>
                                            <option value="Completed" <?= $o['status']=='Completed'?'selected':''; ?>>Completed</option>
                                            <option value="Cancelled" <?= $o['status']=='Cancelled'?'selected':''; ?>>Cancelled</option>
                                        </select>
                                        
                                        <button type="submit" class="btn-edit">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Belum ada pesanan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>