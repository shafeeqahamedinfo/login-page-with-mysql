<?php
// config.php
// Development settings: show errors (disable on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session (single place)
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    // 'cookie_secure' => true, // enable on HTTPS
]);

// Database credentials — change these
$DB_HOST = 'localhost';
$DB_NAME = 'myapp';
$DB_USER = 'root';
$DB_PASS = ''; // put your password

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // For development only: show detailed error. In production, log instead.
    die('Database connection failed: ' . $e->getMessage());
}
