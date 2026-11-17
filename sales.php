<?php
require_once 'auth.php';
require_once 'db.php';

$sales = $pdo->query(
    "SELECT product_name, price, quantity, sold_at
     FROM sales
     ORDER BY sold_at DESC, id DESC
     LIMIT 50"
)->fetchAll();

$pageTitle = 'Історія продажів';
require 'header.php';
?>
<h1>Історія продажів</h1>
<a href="report.php" class="back-link">← Назад до звітів</a>

<?php if (empty($sales)): ?>
    <p>Продажів ще не було</p>
<?php else: ?>
    <ul>
        <?php foreach ($sales as $sale):
            $quantity = (int) $sale['quantity'];
            $price = (float) $sale['price'];
        ?>
            <li>
                <div class="card">
                    <span class="category"><?= date('d.m.Y H:i', strtotime($sale['sold_at'])) ?></span>
                    <span class="product-name"><?= htmlspecialchars($sale['product_name']) ?></span>
                    <span class="status"><?= $quantity ?> шт × <?= number_format($price, 2, '.', ' ') ?> грн</span>
                    <span class="price"><?= number_format($price * $quantity, 2, '.', ' ') ?> грн</span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php require 'footer.php'; ?>
