<?php
require_once 'auth.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - 1 WHERE id = :id AND quantity > 0");
        $stmt->execute([':id' => $id]);
    }
}

header('Location: index.php');
exit;
