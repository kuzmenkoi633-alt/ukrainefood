CREATE DATABASE IF NOT EXISTS ukrainefood CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ukrainefood;

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

INSERT INTO category (name) VALUES
('Страви'),
('Напої'),
('Солодощі');

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    category_id INT NULL,
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES category(id)
        ON DELETE SET NULL
);

INSERT INTO products (name, price, quantity, category_id) VALUES
('Вареники з картоплею', 95.00, 50, 1),
('Борщ український (банка 0,5 л)', 85.00, 100, 1),
('Сало з часником', 120.00, 30, 1),
('Мед липовий (250 г)', 140.00, 20, 3),
('Узвар (1 л)', 60.00, 80, 2);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Демо-адміністратор для локального запуску: логін admin, пароль admin123
-- (пароль збережено як хеш password_hash; змініть його перед публікацією сайту онлайн)
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$NfSeElTJKLB23.teFQ7MrubNPiLEOTScDK/hu/R6/ozQeZ45Gu9vy');
