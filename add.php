<?php
require_once 'auth.php';
require_once 'db.php';

$categories = $pdo->query("SELECT id, name FROM category ORDER BY name")->fetchAll();

$errors = [];
$product = ['name' => '', 'price' => '', 'quantity' => '', 'category_id' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $result = validate_product($_POST, $categories);
    $product = $result['data'];
    $errors = $result['errors'];

    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, quantity, category_id) VALUES (:name, :price, :quantity, :category_id)");
        $stmt->execute([
            ':name' => $product['name'],
            ':price' => $product['price'],
            ':quantity' => $product['quantity'],
            ':category_id' => $product['category_id'],
        ]);
        header('Location: index.php');
        exit;
    }
}
$pageTitle = 'Додати товар';
require 'header.php';
?>
<h1>Додати новий товар</h1>
<a href="index.php">Повернутися до списку товарів</a>

<?php foreach ($errors as $e): ?>
    <p class="error"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" action="add.php" class="product-form">
    <?= csrf_field() ?>
    <label for="name">Назва:</label>
    <input type="text" id="name" name="name" maxlength="255" value="<?= htmlspecialchars((string) $product['name']) ?>" required>
    <label for="price">Ціна (грн):</label>
    <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars((string) $product['price']) ?>" required>
    <label for="quantity">Кількість:</label>
    <input type="number" id="quantity" name="quantity" step="1" min="0" value="<?= htmlspecialchars((string) $product['quantity']) ?>" required>
    <label for="category">Категорія:</label>
    <select id="category" name="category_id" required>
        <?php foreach ($categories as $category): ?>
            <option value="<?= (int) $category['id'] ?>" <?= (int) $category['id'] === $product['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Додати товар">
</form>
<?php require 'footer.php'; ?>