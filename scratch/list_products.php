<?php

$pdo = new PDO("mysql:host=127.0.0.1;dbname=itsroop;charset=utf8mb4", "root", "");
$stmt = $pdo->query("SELECT id, name FROM products");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
