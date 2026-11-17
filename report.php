<?php
require_once 'auth.php';
require_once 'db.php';

$stats = $pdo->query(
    "SELECT COUNT(*) AS total_products,
            COALESCE(SUM(quantity), 0) AS total_items,
            COALESCE(SUM(price * quantity), 0) AS total_value
     FROM products"
)->fetch();

$salesStats = $pdo->query(
    "SELECT COUNT(*) AS sales_count,
            COALESCE(SUM(price * quantity), 0) AS revenue
     FROM sales"
)->fetch();

$today = $pdo->query(
    "SELECT COALESCE(SUM(price * quantity), 0) AS revenue
     FROM sales
     WHERE DATE(sold_at) = CURDATE()"
)->fetch();

$topProducts = $pdo->query(
    "SELECT product_name,
            SUM(quantity) AS sold,
            SUM(price * quantity) AS revenue
     FROM sales
     GROUP BY product_id, product_name
     ORDER BY sold DESC
     LIMIT 5"
)->fetchAll();

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

<h2>Продажі</h2>
<div class="stats">
    <div class="stat-card">
        <span class="stat-label">Кількість продажів</span>
        <span class="stat-value"><?= (int) $salesStats['sales_count'] ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Виручка</span>
        <span class="stat-value"><?= number_format((float) $salesStats['revenue'], 2, '.', ' ') ?> грн</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Виручка за сьогодні</span>
        <span class="stat-value"><?= number_format((float) $today['revenue'], 2, '.', ' ') ?> грн</span>
    </div>
</div>

<h2>Топ продажів</h2>
<?php if (!empty($topProducts)): ?>
    <ul class="low-stock">
        <?php foreach ($topProducts as $item): ?>
            <li><?= htmlspecialchars($item['product_name']) ?> — <?= (int) $item['sold'] ?> шт · <?= number_format((float) $item['revenue'], 2, '.', ' ') ?> грн</li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Продажів ще не було</p>
<?php endif; ?>
<a href="sales.php" class="back-link">Вся історія продажів →</a>

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