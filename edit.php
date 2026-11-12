<?php
require_once 'auth.php';
require_once 'db.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

$product = false;
if ($id) {
    $productStmt = $pdo->prepare("SELECT name, price, quantity, category_id FROM products WHERE id = :id");
    $productStmt->execute([':id' => $id]);
    $product = $productStmt->fetch();
}

if (!$product) {
    http_response_code(404);
    $pageTitle = 'Товар не знайдено';
    require 'header.php';
?>
    <h1>😕 Товар не знайдено</h1>
    <p>Можливо, його вже видалили або посилання неправильне.</p>
    <a href="index.php">← Повернутися до списку товарів</a>
<?php
    require 'footer.php';
    exit;
}

$product['category_id'] = $product['category_id'] === null ? null : (int) $product['category_id'];
$categories = $pdo->query("SELECT id, name FROM category ORDER BY name")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $result = validate_product($_POST, $categories);
    $product = $result['data'];
    $errors = $result['errors'];

    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE products SET name = :name, price = :price, quantity = :quantity, category_id = :category_id WHERE id = :id");
        $stmt->execute([
            ':name' => $product['name'],
            ':price' => $product['price'],
            ':quantity' => $product['quantity'],
            ':category_id' => $product['category_id'],
            ':id' => $id,
        ]);
        header('Location: index.php');
        exit;
    }
}
$pageTitle = 'Редагувати товар';
require 'header.php';
?>
<h1>Редагувати товар</h1>
<a href="index.php">Повернутися до списку товарів</a>

<?php foreach ($errors as $e): ?>
    <p class="error"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" action="edit.php?id=<?= (int) $id ?>" class="product-form">
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
    <input type="submit" value="Зберегти">
</form>
<?php require 'footer.php'; ?>