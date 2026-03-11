<?php
// Prevent header issues
if (!headers_sent()) {
    ob_start();
}

$config = require __DIR__ . '/config.php';

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $config['db']['host'],
    $config['db']['name'],
    $config['db']['charset']
);

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {

    $pdo = new PDO(
        $dsn,
        $config['db']['user'],
        $config['db']['pass'],
        $options
    );

} catch (PDOException $e) {

    if (!headers_sent()) {
        http_response_code(500);
    }

    // Never show DB errors in production
    echo "Database connection failed.";

    exit;
}

return $pdo;