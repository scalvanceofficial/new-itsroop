<?php

$pdo = new PDO("mysql:host=127.0.0.1;dbname=itsroop;charset=utf8mb4", "root", "");

echo "=== PRODUCTS ===\n";
$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
print_r($products);

echo "=== PRODUCT IMAGES ===\n";
$images = $pdo->query("SELECT * FROM product_images")->fetchAll(PDO::FETCH_ASSOC);
print_r($images);

echo "=== PROPERTIES ===\n";
$properties = $pdo->query("SELECT * FROM properties")->fetchAll(PDO::FETCH_ASSOC);
print_r($properties);

echo "=== PRODUCT PROPERTY VALUES ===\n";
$p_values = $pdo->query("SELECT * FROM product_property_values")->fetchAll(PDO::FETCH_ASSOC);
print_r($p_values);
