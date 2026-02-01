<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Получаем данные из JSON или POST
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

// Если JSON не удалось распарсить, пробуем POST
if ($input === null && !empty($_POST)) {
    $input = $_POST;
}

// Получаем данные
$serviceType = intval($input['service_type'] ?? $_POST['service_type'] ?? 0);
$name = trim($input['name'] ?? $_POST['name'] ?? '');
$phone = trim($input['phone'] ?? $_POST['phone'] ?? '');
$email = trim($input['email'] ?? $_POST['email'] ?? '');
$description = trim($input['description'] ?? $_POST['description'] ?? '');
$deadline = trim($input['deadline'] ?? $_POST['deadline'] ?? '');

// Валидация
$errors = [];

if (empty($serviceType)) {
    $errors[] = 'Выберите тип услуги';
}

if (empty($name)) {
    $errors[] = 'Введите ваше имя';
}

if (empty($phone)) {
    $errors[] = 'Введите телефон';
} elseif (!preg_match('/^[\d\s\-\+\(\)]+$/', $phone)) {
    $errors[] = 'Некорректный формат телефона';
}

if (empty($email)) {
    $errors[] = 'Введите email';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Некорректный формат email';
}

if (empty($description)) {
    $errors[] = 'Опишите заказ';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Проверяем, что услуга существует и активна
$services = loadServices();
$serviceExists = false;
foreach ($services as $service) {
    if ($service['id'] == $serviceType && $service['active']) {
        $serviceExists = true;
        break;
    }
}

if (!$serviceExists) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Выбранная услуга недоступна']);
    exit;
}

// Создаем заказ
$orders = loadOrders();
if (!is_array($orders)) {
    $orders = [];
}

$orderId = time() + rand(1000, 9999);

$order = [
    'id' => $orderId,
    'service_type' => $serviceType,
    'name' => $name,
    'phone' => $phone,
    'email' => $email,
    'description' => $description,
    'deadline' => $deadline,
    'created_at' => time(),
    'read' => false
];

$orders[] = $order;

// Сохраняем заказы
try {
    // Проверяем, что файл существует или создаем его
    if (!file_exists(ORDERS_FILE)) {
        file_put_contents(ORDERS_FILE, '[]');
    }
    
    // Проверяем права на запись
    if (!is_writable(ORDERS_FILE) && !is_writable(dirname(ORDERS_FILE))) {
        error_log("Orders file is not writable: " . ORDERS_FILE);
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Ошибка: нет прав на запись файла заказов'
        ]);
        exit;
    }
    
    // Сохраняем заказы
    try {
        saveOrders($orders);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Ошибка сохранения: ' . $e->getMessage()
        ]);
        exit;
    }
} catch (Exception $e) {
    error_log("Error saving order: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Ошибка при сохранении заказа: ' . $e->getMessage()
    ]);
    exit;
}

// Отправляем email администратору
$emailSent = false;
try {
    $emailSent = sendOrderEmail($order);
} catch (Exception $e) {
    error_log("Error sending email: " . $e->getMessage());
    // Не прерываем выполнение, если email не отправился
}

echo json_encode([
    'success' => true,
    'order_id' => $orderId,
    'message' => 'Заказ успешно отправлен',
    'email_sent' => $emailSent
]);
?>
