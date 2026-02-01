<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';

$services = loadServices();

// Возвращаем только активные услуги для публичного API
$activeServices = array_filter($services, function($service) {
    return !empty($service['active']);
});

echo json_encode(array_values($activeServices), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
