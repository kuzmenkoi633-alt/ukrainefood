<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    }

    $error = 'Невірний логін або пароль.';
}
?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід | UkraineFood</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&subset=cyrillic&display=swap">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="login-box">
        <h1>🍲 UkraineFood</h1>
        <p>Вхід в адмінку</p>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="login.php" class="product-form">
            <?= csrf_field() ?>
            <label for="username">Логін:</label>
            <input type="text" id="username" name="username"
                value="<?= htmlspecialchars($username) ?>" required autocomplete="username">

            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">

            <input type="submit" value="Увійти">
        </form>
    </div>
</body>

</html>