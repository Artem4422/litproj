<?php
// Скрипт для миграции товаров из vk_products.json в products.json
require_once 'config.php';

$vkProducts = json_decode(file_get_contents('vk_products.json'), true);
$products = [];

if ($vkProducts) {
    foreach ($vkProducts as $index => $vkProduct) {
        // Извлекаем цену (убираем пробелы и ₽)
        $price = preg_replace('/[^\d]/', '', $vkProduct['price'] ?? '0');
        if (empty($price)) {
            $price = '0';
        }
        
        $product = [
            'id' => 1000 + $index + 1, // Начинаем с 1000 чтобы не конфликтовать
            'name' => $vkProduct['title'] ?? 'Товар без названия',
            'description' => '',
            'price' => $price . ' ₽',
            'image' => '' // Изображения остаются на внешних URL
        ];
        
        // Если есть URL изображения, можно сохранить его
        if (!empty($vkProduct['imageUrl'])) {
            $product['image'] = $vkProduct['imageUrl'];
        }
        
        $products[] = $product;
    }
    
    saveProducts($products);
    echo "Мигрировано " . count($products) . " товаров\n";
} else {
    echo "Не удалось загрузить vk_products.json\n";
}
