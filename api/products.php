<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';

$products = loadProducts();
echo json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
