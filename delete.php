<?php
require_once 'auth.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}

header('Location: index.php');
exit;
