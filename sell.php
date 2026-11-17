<?php
require_once 'auth.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - 1 WHERE id = :id AND quantity > 0");
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() === 1) {
                $sale = $pdo->prepare("INSERT INTO sales (product_id, product_name, price, quantity)
                                       SELECT id, name, price, 1 FROM products WHERE id = :id");
                $sale->execute([':id' => $id]);
            }

            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}

header('Location: index.php');
exit;
