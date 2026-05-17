<?php

$pdo = new PDO("mysql:host=127.0.0.1;charset=utf8mb4", "root", "");
$dbs = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);

foreach ($dbs as $db) {
    if (in_array($db, ['information_schema', 'mysql', 'performance_schema', 'sys', 'phpmyadmin'])) continue;
    try {
        $pdo->exec("USE `$db`");
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        if (in_array('products', $tables)) {
            $count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
            echo "Database: $db, Table 'products' exists with $count rows.\n";
            if ($count > 0) {
                $names = $pdo->query("SELECT name FROM products LIMIT 5")->fetchAll(PDO::FETCH_COLUMN);
                echo "  Sample products: " . implode(', ', $names) . "\n";
            }
        }
    } catch (Exception $e) {
        // ignore
    }
}
