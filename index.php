<?php
require_once 'auth.php';
require_once 'db.php';

$search = trim((string) ($_GET['search'] ?? ''));
$categoryId = filter_var($_GET['category'] ?? null, FILTER_VALIDATE_INT) ?: null;

$sql = "SELECT p.id, p.name, p.price, p.quantity, p.image, c.name AS category_name
        FROM products p
        LEFT JOIN category c ON p.category_id = c.id";
$params = [];

if ($search !== '') {
    $sql .= " WHERE p.name LIKE :search";
    $params[':search'] = '%' . addcslashes($search, '%_\\') . '%';
} elseif ($categoryId) {
    $sql .= " WHERE p.category_id = :category";
    $params[':category'] = $categoryId;
}
$sql .= " ORDER BY p.name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT id, name FROM category ORDER BY name")->fetchAll();
$pageTitle = 'Товари';
require 'header.php';
?>
<h1>Ласкаво просимо до UkraineFood</h1>
<p>Відкрийте для себе багатство кулінарної традиції України!</p>
<h2>Наші товари</h2>
<div class="category-filter">
    <span>Фільтрувати за категорією:</span>
    <a href="index.php" class="pill <?= $categoryId === null ? 'active' : '' ?>">Всі</a>
    <?php foreach ($categories as $cat): ?>
        <a href="index.php?category=<?= (int) $cat['id'] ?>" class="pill <?= $categoryId === (int) $cat['id'] ? 'active' : '' ?>"><?= htmlspecialchars($cat['name']) ?></a>
    <?php endforeach; ?>
</div>
<form method="get" action="index.php" class="search-form">
    <input type="text" name="search" placeholder="Пошук товару..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">🔍 Знайти</button>
</form>
<?php if (empty($products)): ?>
    <p>Товарів не знайдено.</p>
<?php else: ?>
    <ul>
        <?php foreach ($products as $product):
            $id = (int) $product['id'];
            $quantity = (int) $product['quantity'];
            $status = $quantity === 0 ? 'Немає в наявності' : "Кількість: {$quantity} шт";
        ?>
            <li>
                <?php if ($product['image']): ?>
                    <img class="product-photo" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <?php endif; ?>
                <span class="product-name"><?= htmlspecialchars($product['name']) ?></span>
                <span class="price"><?= number_format((float) $product['price'], 2, '.', ' ') ?> грн</span>
                <span class="status <?= $quantity === 0 ? 'out' : '' ?>"><?= $status ?></span>
                <span class="category"><?= htmlspecialchars($product['category_name'] ?? 'Без категорії') ?></span>
                <div class="actions">
                    <form method="post" action="sell.php">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="submit" class="btn-sell" value="💰 Продати" <?= $quantity === 0 ? 'disabled' : '' ?>>
                    </form>
                    <a href="edit.php?id=<?= $id ?>">✏️ Редагувати</a>
                    <form method="post" action="delete.php" onsubmit="return confirm('Видалити товар?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="submit" value="❌ Видалити">
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php require 'footer.php'; ?>