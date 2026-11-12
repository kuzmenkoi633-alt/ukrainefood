<?php
session_start();
require_once __DIR__ . '/functions.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
