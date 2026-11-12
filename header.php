<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Магазин') ?> | UkraineFood</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&subset=cyrillic&display=swap">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header class="site-header">
        <a href="index.php" class="logo">🍲 UkraineFood</a>
        <nav class="menu">
            <a href="index.php">🛒 Товари</a>
            <a href="add.php">➕ Додати товар</a>
            <a href="report.php">📊 Звіти</a>
        </nav>
        <div class="user">
            <span>👤 <?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <form method="post" action="logout.php">
                <?= csrf_field() ?>
                <button type="submit">Вийти</button>
            </form>
        </div>
    </header>
    <main>