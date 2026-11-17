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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Noto+Sans:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="styles.css">
    <script>
        try {
            const saved = localStorage.getItem('theme');
            document.documentElement.dataset.theme = saved || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        } catch (e) {}
    </script>
</head>

<body>
    <div class="login-box">
        <svg class="login-star" viewBox="0 0 50 50" aria-hidden="true"><circle cx="25" cy="25" r="23" fill="none" stroke="currentColor" stroke-width="2.5" /><path d="M25.0 10.0 L27.9 18.1 L35.6 14.4 L31.9 22.1 L40.0 25.0 L31.9 27.9 L35.6 35.6 L27.9 31.9 L25.0 40.0 L22.1 31.9 L14.4 35.6 L18.1 27.9 L10.0 25.0 L18.1 22.1 L14.4 14.4 L22.1 18.1 Z M25 20 L30 25 L25 30 L20 25 Z" fill="currentColor" fill-rule="evenodd" /></svg>
        <span class="hero-kicker">господа комори</span>
        <h1>UkraineFood</h1>
        <p class="login-sub">Вхід для господаря</p>

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