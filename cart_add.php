<?php
require_once 'auth.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("SELECT quantity FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $stock = $stmt->fetchColumn();

        if ($stock !== false) {
            $inCart = $_SESSION['cart'][$id] ?? 0;
            if ($inCart < (int) $stock) {
                $_SESSION['cart'][$id] = $inCart + 1;
            }
        }
    }
}

header('Location: index.php#products');
exit;
