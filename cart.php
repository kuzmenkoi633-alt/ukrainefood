<?php
require_once 'auth.php';
require_once 'db.php';

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0.0;

if (!empty($cart)) {
    $ids = array_map('intval', array_keys($cart));
    $placeholders = implode(', ', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);

    foreach ($stmt->fetchAll() as $product) {
        $qty = (int) $cart[$product['id']];
        $sum = (float) $product['price'] * $qty;
        $total += $sum;
        $items[] = [
            'id'    => (int) $product['id'],
            'name'  => $product['name'],
            'price' => (float) $product['price'],
            'qty'   => $qty,
            'sum'   => $sum,
        ];
    }

    $_SESSION['cart'] = array_intersect_key($cart, array_column($items, 'qty', 'id'));
}

$pageTitle = 'Кошик';
require 'header.php';
?>
<h1>Кошик</h1>
<a href="index.php#products" class="back-link">← Назад до товарів</a>

<?php if (!empty($_SESSION['cart_error'])): ?>
    <p class="error"><?= htmlspecialchars($_SESSION['cart_error']) ?></p>
    <?php unset($_SESSION['cart_error']); ?>
<?php endif; ?>

<?php if (empty($items)): ?>
    <p>Кошик порожній</p>
<?php else: ?>
    <ul>
        <?php foreach ($items as $item): ?>
            <li>
                <div class="card">
                    <span class="product-name"><?= htmlspecialchars($item['name']) ?></span>
                    <span class="price"><?= number_format($item['price'], 2, '.', ' ') ?> грн</span>
                    <span class="status">Кількість: <?= $item['qty'] ?> шт</span>
                    <span class="status">Сума: <?= number_format($item['sum'], 2, '.', ' ') ?> грн</span>
                    <div class="actions">
                        <form method="post" action="cart_remove.php">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="submit" value="Прибрати">
                        </form>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="stats">
        <div class="stat-card">
            <span class="stat-label">Разом</span>
            <span class="stat-value"><?= number_format($total, 2, '.', ' ') ?> грн</span>
        </div>
    </div>

    <div class="actions">
        <form method="post" action="checkout.php">
            <?= csrf_field() ?>
            <input type="submit" class="btn-sell" value="Оформити">
        </form>
    </div>
<?php endif; ?>
<?php require 'footer.php'; ?>
