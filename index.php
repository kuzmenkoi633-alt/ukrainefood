<?php
require_once 'auth.php';
require_once 'db.php';

$search = trim((string) ($_GET['search'] ?? ''));
$categoryId = filter_var($_GET['category'] ?? null, FILTER_VALIDATE_INT) ?: null;

$sql = "SELECT p.id, p.name, p.price, p.quantity, p.image, c.name AS category_name
        FROM products p
        LEFT JOIN category c ON p.category_id = c.id";
$params = [];

if ($search !== '') {
    $sql .= " WHERE p.name LIKE :search";
    $params[':search'] = '%' . addcslashes($search, '%_\\') . '%';
} elseif ($categoryId) {
    $sql .= " WHERE p.category_id = :category";
    $params[':category'] = $categoryId;
}
$sql .= " ORDER BY p.name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT id, name FROM category ORDER BY name")->fetchAll();
$pageTitle = 'Товари';
require 'header.php';
?>
<section class="hero">
    <div class="hero-text">
        <span class="hero-kicker">Зима · українське село</span>
        <h1>Склад сьогодні</h1>
        <p class="hero-sub">Сніг на стрісі, світло у вікні. Комора повна.</p>
        <form method="get" action="index.php#products" class="search-form">
            <input type="text" name="search" placeholder="Пошук товару..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Знайти</button>
        </form>
    </div>

    <div class="hero-sun" aria-hidden="true">
        <svg viewBox="0 0 100 100">
            <path d="M50 50 a4 4 0 0 1 8 0 a8 8 0 0 1 -16 0 a12 12 0 0 1 24 0 a16 16 0 0 1 -32 0 a20 20 0 0 1 40 0 a24 24 0 0 1 -44 4" fill="none" stroke="#1a1a1a" stroke-width="4" stroke-linecap="round" opacity="0.85" />
        </svg>
    </div>

    <span class="hero-flake" style="left: 30%; animation-delay: 0s;"></span>
    <span class="hero-flake" style="left: 52%; animation-delay: -3s;"></span>
    <span class="hero-flake" style="left: 70%; animation-delay: -6s;"></span>
    <span class="hero-flake" style="left: 88%; animation-delay: -1.5s;"></span>
    <span class="hero-flake" style="left: 12%; animation-delay: -4.5s;"></span>

    <svg class="hero-scene" viewBox="0 0 800 300" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
        <!-- гори -->
        <path class="mtn1" d="M0 300 L0 150 Q90 120 170 140 Q260 80 360 120 Q460 60 560 110 Q660 70 800 120 L800 300 Z" />
        <path class="mtn2" d="M0 300 L0 200 Q120 165 240 190 Q360 150 480 185 Q620 150 800 180 L800 300 Z" />
        <path class="ground" d="M0 300 L0 250 Q160 225 330 245 Q520 265 800 240 L800 300 Z" />

        <!-- хата-мазанка -->
        <defs>
            <radialGradient id="hero-glow">
                <stop class="glow-core" offset="0" />
                <stop class="glow-edge" offset="1" />
            </radialGradient>
        </defs>
        <ellipse class="glow" cx="162" cy="246" rx="60" ry="14" fill="url(#hero-glow)" />
        <rect class="wall" x="118" y="212" width="88" height="34" />
        <path class="roof" d="M104 216 Q118 196 162 182 Q206 196 220 216 Z" />
        <path class="snow" d="M112 206 Q130 190 162 180 Q194 190 212 206 Q194 196 162 186 Q130 196 112 206 Z" />
        <rect class="window" x="130" y="222" width="16" height="13" />
        <rect class="window" x="178" y="222" width="16" height="13" style="animation-delay: -1.1s;" />
        <path class="line" d="M138 222 L138 235 M130 228.5 L146 228.5 M186 222 L186 235 M178 228.5 L194 228.5" stroke-width="1" />
        <rect class="ink" x="155" y="224" width="12" height="22" />
        <rect class="wall" x="180" y="178" width="8" height="12" />
        <path class="smoke" d="M184 176 C178 164 190 156 184 144 C178 132 190 124 184 112" />
        <path class="line" d="M88 246 L88 228 M97 246 L97 230 M228 246 L228 229 M237 246 L237 231 M84 236 L241 236" stroke-width="1.4" />

        <!-- стежка -->
        <path class="trail" d="M244 248 Q330 254 420 248 Q520 242 616 250" />

        <!-- криниця-журавель -->
        <rect class="ink" x="690" y="150" width="4.5" height="96" />
        <path class="line" d="M686 152 L692 160 L698 152" stroke-width="2" />
        <path class="line" d="M636 202 L772 144" stroke-width="3" />
        <rect class="ink" x="766" y="138" width="10" height="9" transform="rotate(-23 771 142)" />
        <path class="line" d="M637 202 L637 226" stroke-width="1" />
        <path class="ink" d="M631 226 L643 226 L641 235 L633 235 Z" />
        <rect class="wall" x="618" y="232" width="40" height="14" />
        <path class="snow" d="M616 232 Q638 226 660 232 Z" />

        <!-- калина -->
        <path class="line" d="M800 262 Q770 250 745 236 Q730 228 716 226 M770 250 Q776 234 790 226 M745 236 Q748 222 760 214" stroke-width="2" />
        <g class="berry">
            <circle cx="716" cy="230" r="3.2" />
            <circle cx="722" cy="233" r="3.2" />
            <circle cx="718" cy="236" r="3.2" />
            <circle cx="711" cy="235" r="3" />
            <circle cx="760" cy="218" r="3.2" />
            <circle cx="766" cy="221" r="3.2" />
            <circle cx="762" cy="224" r="3.2" />
            <circle cx="756" cy="223" r="3" />
            <circle cx="790" cy="230" r="3.2" />
            <circle cx="795" cy="234" r="3.2" />
            <circle cx="788" cy="236" r="3" />
        </g>
    </svg>
</section>
<h2 id="products">Наші товари</h2>
<div class="category-filter">
    <span>Фільтрувати за категорією:</span>
    <a href="index.php#products" class="pill <?= $categoryId === null ? 'active' : '' ?>">Всі</a>
    <?php foreach ($categories as $cat): ?>
        <a href="index.php?category=<?= (int) $cat['id'] ?>#products" class="pill <?= $categoryId === (int) $cat['id'] ? 'active' : '' ?>"><?= htmlspecialchars($cat['name']) ?></a>
    <?php endforeach; ?>
</div>
<div class="view-toggle">
    <button type="button" data-view="grid">Плитка</button>
    <button type="button" data-view="list">Список</button>
</div>
<?php if (empty($products)): ?>
    <p>Товарів не знайдено.</p>
<?php else: ?>
    <ul id="product-list" data-view="grid">
        <?php foreach ($products as $product):
            $id = (int) $product['id'];
            $quantity = (int) $product['quantity'];
            $status = $quantity === 0 ? 'Немає в наявності' : "Кількість: {$quantity} шт";
        ?>
            <li>
                <div class="card">
                    <?php if ($product['image']): ?>
                        <img class="product-photo" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php endif; ?>
                    <span class="product-name"><?= htmlspecialchars($product['name']) ?></span>
                    <span class="price"><?= number_format((float) $product['price'], 2, '.', ' ') ?> грн</span>
                    <span class="status <?= $quantity === 0 ? 'out' : '' ?>"><?= $status ?></span>
                    <span class="category"><?= htmlspecialchars($product['category_name'] ?? 'Без категорії') ?></span>
                    <div class="actions">
                        <form method="post" action="sell.php">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="submit" class="btn-sell" value="Продати" <?= $quantity === 0 ? 'disabled' : '' ?>>
                        </form>
                        <a href="edit.php?id=<?= $id ?>">Редагувати</a>
                        <form method="post" action="delete.php" onsubmit="return confirm('Видалити товар?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="submit" value="Видалити">
                        </form>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<script src="view.js"></script>
<?php require 'footer.php'; ?>