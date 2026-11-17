<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Магазин') ?> | UkraineFood</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Noto+Sans:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header class="site-header" id="top">
        <a href="index.php" class="logo"><svg class="logo-star" viewBox="0 0 50 50" aria-hidden="true"><circle cx="25" cy="25" r="23" fill="none" stroke="currentColor" stroke-width="2.5" /><path d="M25.0 10.0 L27.9 18.1 L35.6 14.4 L31.9 22.1 L40.0 25.0 L31.9 27.9 L35.6 35.6 L27.9 31.9 L25.0 40.0 L22.1 31.9 L14.4 35.6 L18.1 27.9 L10.0 25.0 L18.1 22.1 L14.4 14.4 L22.1 18.1 Z M25 20 L30 25 L25 30 L20 25 Z" fill="currentColor" fill-rule="evenodd" /></svg><span>UkraineFood</span></a>
        <nav class="menu">
            <a href="index.php">Товари</a>
            <a href="add.php">Додати товар</a>
            <a href="report.php">Звіти</a>
            <a href="cart.php">Кошик (<?= (int) array_sum($_SESSION['cart'] ?? []) ?>)</a>
        </nav>
        <div class="user">
            <button type="button" id="theme-toggle" class="theme-switch" title="Змінити тему">
                <span class="knob"></span>
            </button>
            <span><?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <form method="post" action="logout.php">
                <?= csrf_field() ?>
                <button type="submit">Вийти</button>
            </form>
        </div>
    </header>
    <script src="theme.js"></script>
    <main>