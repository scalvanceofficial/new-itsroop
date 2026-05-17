<?php

$pdo = new PDO("mysql:host=127.0.0.1;dbname=itsroop;charset=utf8mb4", "root", "");
echo "Products count: " . $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() . "\n";
echo "Categories count: " . $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn() . "\n";
echo "Product Images count: " . $pdo->query("SELECT COUNT(*) FROM product_images")->fetchColumn() . "\n";
echo "Product Prices count: " . $pdo->query("SELECT COUNT(*) FROM product_prices")->fetchColumn() . "\n";
echo "Sliders count: " . $pdo->query("SELECT COUNT(*) FROM sliders")->fetchColumn() . "\n";
