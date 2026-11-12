<?php
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_check(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Недійсний запит (CSRF). Оновіть сторінку і спробуйте ще раз.');
    }
}
function validate_product(array $input, array $categories): array
{
    $errors = [];

    $name = trim((string) ($input['name'] ?? ''));
    $price = filter_var($input['price'] ?? '', FILTER_VALIDATE_FLOAT);
    $quantity = filter_var($input['quantity'] ?? '', FILTER_VALIDATE_INT);
    $categoryId = filter_var($input['category_id'] ?? '', FILTER_VALIDATE_INT);

    if ($name === '') {
        $errors[] = 'Вкажіть назву товару.';
    } elseif (mb_strlen($name) > 255) {
        $errors[] = 'Назва не може бути довшою за 255 символів.';
    }

    if ($price === false || $price < 0 || $price > 99999999.99) {
        $errors[] = 'Ціна має бути числом від 0 до 99 999 999.99.';
    }

    if ($quantity === false || $quantity < 0) {
        $errors[] = 'Кількість має бути цілим числом не менше 0.';
    }

    if (!in_array($categoryId, array_map('intval', array_column($categories, 'id')), true)) {
        $errors[] = 'Оберіть існуючу категорію.';
    }

    return [
        'data' => [
            'name' => $name,
            'price' => $price === false ? ($input['price'] ?? '') : $price,
            'quantity' => $quantity === false ? ($input['quantity'] ?? '') : $quantity,
            'category_id' => $categoryId === false ? null : $categoryId,
        ],
        'errors' => $errors,
    ];
}
