<?php
require_once 'auth.php';
require_once 'db.php';

$stats = $pdo->query(
    "SELECT COUNT(*) AS total_products,
            COALESCE(SUM(quantity), 0) AS total_items,
            COALESCE(SUM(price * quantity), 0) AS total_value
     FROM products"
)->fetch();

$lowStock = $pdo->query("SELECT name, quantity FROM products WHERE quantity < 10 ORDER BY quantity")->fetchAll();
$pageTitle = 'Звіти';
require 'header.php';
?>
<h1>Звіти</h1>
<a href="index.php" class="back-link">← Назад до товарів</a>

<div class="stats">
    <div class="stat-card">
        <span class="stat-label">Найменувань товарів</span>
        <span class="stat-value"><?= (int) $stats['total_products'] ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Одиниць на складі</span>
        <span class="stat-value"><?= (int) $stats['total_items'] ?> шт.</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Загальна вартість</span>
        <span class="stat-value"><?= number_format((float) $stats['total_value'], 2, '.', ' ') ?> грн</span>
    </div>
</div>

<h2>Закінчується (менше 10 шт.)</h2>
<?php if (!empty($lowStock)): ?>
    <ul class="low-stock">
        <?php foreach ($lowStock as $item): ?>
            <li><?= htmlspecialchars($item['name']) ?>: <?= (int) $item['quantity'] ?> шт.</li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Немає товарів з низьким запасом.</p>
<?php endif; ?>
<?php require 'footer.php'; ?>