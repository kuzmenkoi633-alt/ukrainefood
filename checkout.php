<?php
require_once 'auth.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

csrf_check();

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // :min_qty — те саме значення, що й :qty: з ATTR_EMULATE_PREPARES = false
    // один іменований плейсхолдер не можна вжити в запиті двічі.
    $update = $pdo->prepare("UPDATE products SET quantity = quantity - :qty
                             WHERE id = :id AND quantity >= :min_qty");

    foreach ($cart as $id => $qty) {
        $update->execute([':qty' => (int) $qty, ':min_qty' => (int) $qty, ':id' => (int) $id]);

        if ($update->rowCount() !== 1) {
            $pdo->rollBack();
            $_SESSION['cart_error'] = 'Не вистачає товару на складі';
            header('Location: cart.php');
            exit;
        }
    }

    $sale = $pdo->prepare("INSERT INTO sales (product_id, product_name, price, quantity)
                           SELECT id, name, price, :qty FROM products WHERE id = :id");

    foreach ($cart as $id => $qty) {
        $sale->execute([':qty' => (int) $qty, ':id' => (int) $id]);
    }

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $e;
}

unset($_SESSION['cart']);

header('Location: report.php');
exit;
