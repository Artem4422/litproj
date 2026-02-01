<?php
require_once 'config.php';

// Парсим HTML и извлекаем товары
$html = file_get_contents(__DIR__ . '/index.html');
$products = [];

// Используем DOMDocument для надежного парсинга
libxml_use_internal_errors(true);
$dom = new DOMDocument();
@$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

$productCards = $xpath->query('//article[contains(@class, "product-card")]');

foreach ($productCards as $index => $card) {
    $id = $card->getAttribute('data-product-id');
    if (empty($id)) {
        $id = $index + 1;
    }
    
    $name = $card->getAttribute('data-product-name');
    $price = $card->getAttribute('data-product-price');
    $description = $card->getAttribute('data-product-description');
    
    // Извлекаем название из заголовка если нет в data-атрибуте
    if (empty($name)) {
        $nameNodes = $xpath->query('.//h3[contains(@class, "product-name")]', $card);
        if ($nameNodes->length > 0) {
            $name = trim($nameNodes->item(0)->textContent);
        }
    }
    
    // Извлекаем описание если нет в data-атрибуте
    if (empty($description)) {
        $descNodes = $xpath->query('.//p[contains(@class, "product-description")]', $card);
        if ($descNodes->length > 0) {
            $description = trim($descNodes->item(0)->textContent);
        }
    }
    
    // Извлекаем цену из текста
    $priceFormatted = '';
    $priceNodes = $xpath->query('.//div[contains(@class, "product-price")]', $card);
    if ($priceNodes->length > 0) {
        $priceText = trim($priceNodes->item(0)->textContent);
        // Убираем мета-теги из текста
        $priceText = preg_replace('/<[^>]+>/', '', $priceText);
        $priceFormatted = trim($priceText);
    } else if (!empty($price)) {
        $priceFormatted = number_format(intval($price), 0, '', ' ') . ' ₽';
    }
    
    // Извлекаем изображение
    $image = '';
    $imgNodes = $xpath->query('.//img[contains(@class, "product-img")]', $card);
    if ($imgNodes->length > 0) {
        $image = $imgNodes->item(0)->getAttribute('src');
    }
    
    if (!empty($name)) {
        $products[] = [
            'id' => intval($id),
            'name' => $name,
            'description' => $description ?: '',
            'price' => $priceFormatted ?: '0 ₽',
            'image' => $image
        ];
    }
}

// Сохраняем товары
if (!empty($products)) {
    saveProducts($products);
    echo "Импортировано " . count($products) . " товаров\n";
    echo "Товары сохранены в products.json\n\n";
    
    // Выводим список импортированных товаров
    foreach ($products as $product) {
        echo "- " . $product['name'] . " (" . $product['price'] . ")\n";
    }
} else {
    echo "Товары не найдены в HTML\n";
}
