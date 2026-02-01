<?php
session_start();

// Конфигурация
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // Измените пароль!
define('PRODUCTS_FILE', __DIR__ . '/products.json');
define('CONTACTS_FILE', __DIR__ . '/contacts.json');
define('SERVICES_FILE', __DIR__ . '/services.json');
define('ORDERS_FILE', __DIR__ . '/orders.json');
define('SEO_FILE', __DIR__ . '/seo.json');
define('UPLOAD_DIR', __DIR__ . '/uploads/products/');
define('ADMIN_DIR', __DIR__ . '/admin/');
define('ADMIN_EMAIL', 'info@triol-foundry.ru'); // Email администратора для уведомлений
define('SMTP_HOST', 'smtp.gmail.com'); // SMTP сервер (можно изменить)
define('SMTP_PORT', 587); // Порт SMTP
define('SMTP_USER', ''); // Email для SMTP авторизации (если нужна)
define('SMTP_PASS', ''); // Пароль для SMTP (если нужна)
define('SMTP_FROM_EMAIL', 'info@triol-foundry.ru'); // Email отправителя
define('SMTP_FROM_NAME', 'Литейная мастерская ТРИОЛЬ'); // Имя отправителя

// Создаем директории если их нет
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Функция проверки авторизации
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Функция редиректа на логин
function redirectToLogin() {
    $adminPath = '/admin/';
    if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
        $adminPath = 'index.php';
    }
    header('Location: ' . $adminPath);
    exit;
}

// Функция загрузки товаров
function loadProducts() {
    if (file_exists(PRODUCTS_FILE)) {
        $data = file_get_contents(PRODUCTS_FILE);
        return json_decode($data, true) ?: [];
    }
    return [];
}

// Функция сохранения товаров
function saveProducts($products) {
    file_put_contents(PRODUCTS_FILE, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Функция загрузки контактов
function loadContacts() {
    if (file_exists(CONTACTS_FILE)) {
        $data = file_get_contents(CONTACTS_FILE);
        return json_decode($data, true) ?: [];
    }
    // Значения по умолчанию
    return [
        'address' => 'г. Москва, ул. Примерная, д. 1',
        'phone' => '+7 (999) 123-45-67',
        'email' => 'info@triol-foundry.ru',
        'hours' => 'Пн-Пт: 9:00 - 18:00<br>Сб: 10:00 - 16:00'
    ];
}

// Функция сохранения контактов
function saveContacts($contacts) {
    file_put_contents(CONTACTS_FILE, json_encode($contacts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Функция загрузки типов услуг
function loadServices() {
    if (file_exists(SERVICES_FILE)) {
        $data = file_get_contents(SERVICES_FILE);
        return json_decode($data, true) ?: [];
    }
    // Значения по умолчанию
    return [
        ['id' => 1, 'name' => 'Художественное литье', 'active' => true],
        ['id' => 2, 'name' => 'Промышленное литье', 'active' => true],
        ['id' => 3, 'name' => 'Архитектурное литье', 'active' => true],
        ['id' => 4, 'name' => 'Ремонт и реставрация', 'active' => true],
        ['id' => 5, 'name' => 'Ювелирное литье', 'active' => true],
        ['id' => 6, 'name' => 'Дизайн и консультации', 'active' => true],
        ['id' => 7, 'name' => 'Другое', 'active' => true]
    ];
}

// Функция сохранения типов услуг
function saveServices($services) {
    file_put_contents(SERVICES_FILE, json_encode($services, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Функция загрузки заказов
function loadOrders() {
    clearstatcache(); // Очищаем кэш файловой системы
    if (file_exists(ORDERS_FILE)) {
        $data = file_get_contents(ORDERS_FILE);
        $decoded = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error in loadOrders: " . json_last_error_msg());
            return [];
        }
        return is_array($decoded) ? $decoded : [];
    }
    return [];
}

// Функция сохранения заказов
function saveOrders($orders) {
    $json = json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $result = file_put_contents(ORDERS_FILE, $json);
    
    if ($result === false) {
        error_log("Failed to save orders to " . ORDERS_FILE);
        throw new Exception("Не удалось сохранить заказы. Проверьте права доступа к файлу.");
    }
    
    return true;
}

// Функция загрузки SEO настроек
function loadSEO() {
    if (file_exists(SEO_FILE)) {
        $data = file_get_contents(SEO_FILE);
        $decoded = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return getDefaultSEO();
        }
        return is_array($decoded) ? $decoded : getDefaultSEO();
    }
    return getDefaultSEO();
}

// Функция получения SEO настроек по умолчанию
function getDefaultSEO() {
    return [
        'title' => 'ЛИТЕЙНАЯ МАСТЕРСКАЯ ТРИОЛЬ - Художественное литье из бронзы | Москва',
        'description' => 'Литейная мастерская ТРИОЛЬ - художественное литье из бронзы, архитектурные элементы, памятные таблички. Традиции мастерства в современном исполнении. Изготовление на заказ в Москве.',
        'keywords' => 'литейная мастерская, художественное литье, бронза, архитектурные элементы, памятные таблички, Москва, литье на заказ, бронзовые изделия, каминные решетки, сувенирная продукция',
        'author' => 'ТРИОЛЬ',
        'robots' => 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1',
        'language' => 'Russian',
        'revisit_after' => '7 days',
        'geo_region' => 'RU-MOW',
        'geo_placename' => 'Москва',
        'geo_position' => '55.7558;37.6173',
        'og_type' => 'website',
        'og_url' => 'https://triol-foundry.ru/',
        'og_title' => 'ЛИТЕЙНАЯ МАСТЕРСКАЯ ТРИОЛЬ - Художественное литье из бронзы',
        'og_description' => 'Традиции мастерства в современном исполнении. Художественное литье из бронзы, архитектурные элементы, памятные таблички. Изготовление на заказ в Москве.',
        'og_image' => 'https://triol-foundry.ru/AVA.jpg',
        'og_image_width' => '1200',
        'og_image_height' => '630',
        'og_image_alt' => 'Литейная мастерская ТРИОЛЬ - художественное литье из бронзы',
        'og_site_name' => 'ТРИОЛЬ',
        'twitter_card' => 'summary_large_image',
        'twitter_title' => 'ЛИТЕЙНАЯ МАСТЕРСКАЯ ТРИОЛЬ - Художественное литье из бронзы',
        'twitter_description' => 'Традиции мастерства в современном исполнении. Художественное литье из бронзы, архитектурные элементы, памятные таблички.',
        'twitter_image' => 'https://triol-foundry.ru/AVA.jpg',
        'canonical_url' => 'https://triol-foundry.ru/',
        'favicon' => '/favicon.ico',
        'google_verification' => '',
        'yandex_verification' => '',
        'vk_verification' => ''
    ];
}

// Функция сохранения SEO настроек
function saveSEO($seo) {
    $json = json_encode($seo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $result = file_put_contents(SEO_FILE, $json);
    
    if ($result === false) {
        error_log("Failed to save SEO to " . SEO_FILE);
        throw new Exception("Не удалось сохранить SEO настройки. Проверьте права доступа к файлу.");
    }
    
    return true;
}

// Функция отправки email через SMTP или стандартную функцию mail()
function sendOrderEmail($order) {
    $contacts = loadContacts();
    $adminEmail = ADMIN_EMAIL;
    $subject = 'Новый заказ услуги #' . $order['id'];
    
    $serviceName = 'Не указано';
    $services = loadServices();
    foreach ($services as $service) {
        if ($service['id'] == $order['service_type']) {
            $serviceName = $service['name'];
            break;
        }
    }
    
    // Формируем HTML сообщение
    $htmlMessage = "
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
            .field { margin: 10px 0; }
            .label { font-weight: bold; color: #555; }
            .value { margin-top: 5px; }
            .footer { margin-top: 20px; padding: 10px; text-align: center; color: #777; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Новый заказ услуги</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='label'>Номер заказа:</div>
                    <div class='value'>#" . htmlspecialchars($order['id']) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Дата:</div>
                    <div class='value'>" . date('d.m.Y H:i', $order['created_at']) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Тип услуги:</div>
                    <div class='value'>" . htmlspecialchars($serviceName) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Имя:</div>
                    <div class='value'>" . htmlspecialchars($order['name']) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Телефон:</div>
                    <div class='value'><a href='tel:" . htmlspecialchars($order['phone']) . "'>" . htmlspecialchars($order['phone']) . "</a></div>
                </div>
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'><a href='mailto:" . htmlspecialchars($order['email']) . "'>" . htmlspecialchars($order['email']) . "</a></div>
                </div>
                <div class='field'>
                    <div class='label'>Описание заказа:</div>
                    <div class='value'>" . nl2br(htmlspecialchars($order['description'])) . "</div>
                </div>";
    
    if (!empty($order['deadline'])) {
        $htmlMessage .= "
                <div class='field'>
                    <div class='label'>Желаемые сроки:</div>
                    <div class='value'>" . htmlspecialchars($order['deadline']) . "</div>
                </div>";
    }
    
    $htmlMessage .= "
            </div>
            <div class='footer'>
                <p>Это автоматическое уведомление от системы заказов</p>
            </div>
        </div>
    </body>
    </html>";
    
    // Текстовая версия для совместимости
    $textMessage = "Новый заказ услуги\n\n";
    $textMessage .= "Номер заказа: #" . $order['id'] . "\n";
    $textMessage .= "Дата: " . date('d.m.Y H:i', $order['created_at']) . "\n\n";
    $textMessage .= "Тип услуги: " . $serviceName . "\n";
    $textMessage .= "Имя: " . $order['name'] . "\n";
    $textMessage .= "Телефон: " . $order['phone'] . "\n";
    $textMessage .= "Email: " . $order['email'] . "\n";
    $textMessage .= "Описание: " . $order['description'] . "\n";
    if (!empty($order['deadline'])) {
        $textMessage .= "Желаемые сроки: " . $order['deadline'] . "\n";
    }
    
    // Заголовки для HTML письма
    $boundary = uniqid('boundary_');
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . $order['email'] . "\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    // Формируем тело письма с альтернативными версиями
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $textMessage . "\r\n\r\n";
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $htmlMessage . "\r\n\r\n";
    $body .= "--$boundary--";
    
    // Пробуем отправить через стандартную функцию mail()
    $result = @mail($adminEmail, $subject, $body, $headers);
    
    // Если SMTP настроен, можно использовать более продвинутый метод
    // Для этого нужно установить библиотеку PHPMailer или использовать встроенные функции
    
    return $result;
}
?>
