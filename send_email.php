<?php
/**
 * Улучшенная функция отправки email
 * Поддерживает как стандартную функцию mail(), так и SMTP (если настроен)
 */

require_once 'config.php';

function sendOrderEmailAdvanced($order) {
    $adminEmail = ADMIN_EMAIL;
    $subject = 'Новый заказ услуги #' . $order['id'];
    
    // Получаем название услуги
    $serviceName = 'Не указано';
    $services = loadServices();
    foreach ($services as $service) {
        if ($service['id'] == $order['service_type']) {
            $serviceName = $service['name'];
            break;
        }
    }
    
    // HTML версия письма
    $htmlMessage = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
            .header { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 30px 20px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; }
            .content { padding: 30px 20px; background: #f9f9f9; }
            .order-info { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .field { margin: 15px 0; padding-bottom: 15px; border-bottom: 1px solid #eee; }
            .field:last-child { border-bottom: none; }
            .label { font-weight: bold; color: #2c3e50; font-size: 14px; margin-bottom: 5px; }
            .value { color: #555; font-size: 16px; }
            .value a { color: #3498db; text-decoration: none; }
            .value a:hover { text-decoration: underline; }
            .footer { background: #2c3e50; color: white; padding: 20px; text-align: center; font-size: 12px; }
            .highlight { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Новый заказ услуги</h1>
            </div>
            <div class='content'>
                <div class='order-info'>
                    <div class='field'>
                        <div class='label'>Номер заказа</div>
                        <div class='value'><strong>#" . htmlspecialchars($order['id']) . "</strong></div>
                    </div>
                    <div class='field'>
                        <div class='label'>Дата заказа</div>
                        <div class='value'>" . date('d.m.Y H:i', $order['created_at']) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Тип услуги</div>
                        <div class='value'>" . htmlspecialchars($serviceName) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Контактная информация</div>
                        <div class='value'>
                            <strong>Имя:</strong> " . htmlspecialchars($order['name']) . "<br>
                            <strong>Телефон:</strong> <a href='tel:" . htmlspecialchars($order['phone']) . "'>" . htmlspecialchars($order['phone']) . "</a><br>
                            <strong>Email:</strong> <a href='mailto:" . htmlspecialchars($order['email']) . "'>" . htmlspecialchars($order['email']) . "</a>
                        </div>
                    </div>
                    <div class='field'>
                        <div class='label'>Описание заказа</div>
                        <div class='value'>" . nl2br(htmlspecialchars($order['description'])) . "</div>
                    </div>";
    
    if (!empty($order['deadline'])) {
        $htmlMessage .= "
                    <div class='field highlight'>
                        <div class='label'>Желаемые сроки выполнения</div>
                        <div class='value'>" . htmlspecialchars($order['deadline']) . "</div>
                    </div>";
    }
    
    $htmlMessage .= "
                </div>
            </div>
            <div class='footer'>
                <p>Это автоматическое уведомление от системы управления заказами</p>
                <p>Литейная мастерская ТРИОЛЬ</p>
            </div>
        </div>
    </body>
    </html>";
    
    // Текстовая версия для почтовых клиентов без поддержки HTML
    $textMessage = "НОВЫЙ ЗАКАЗ УСЛУГИ\n";
    $textMessage .= str_repeat("=", 50) . "\n\n";
    $textMessage .= "Номер заказа: #" . $order['id'] . "\n";
    $textMessage .= "Дата: " . date('d.m.Y H:i', $order['created_at']) . "\n\n";
    $textMessage .= "Тип услуги: " . $serviceName . "\n\n";
    $textMessage .= "Контактная информация:\n";
    $textMessage .= "  Имя: " . $order['name'] . "\n";
    $textMessage .= "  Телефон: " . $order['phone'] . "\n";
    $textMessage .= "  Email: " . $order['email'] . "\n\n";
    $textMessage .= "Описание заказа:\n";
    $textMessage .= $order['description'] . "\n\n";
    if (!empty($order['deadline'])) {
        $textMessage .= "Желаемые сроки: " . $order['deadline'] . "\n\n";
    }
    $textMessage .= str_repeat("=", 50) . "\n";
    $textMessage .= "Литейная мастерская ТРИОЛЬ\n";
    
    // Формируем заголовки
    $boundary = uniqid('boundary_');
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . $order['email'] . "\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "X-Priority: 1\r\n"; // Высокий приоритет
    
    // Формируем тело письма
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $textMessage . "\r\n\r\n";
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $htmlMessage . "\r\n\r\n";
    $body .= "--$boundary--";
    
    // Отправляем письмо
    $result = @mail($adminEmail, $subject, $body, $headers);
    
    // Логируем результат
    if ($result) {
        error_log("Email sent successfully to $adminEmail for order #" . $order['id']);
    } else {
        error_log("Failed to send email to $adminEmail for order #" . $order['id']);
    }
    
    return $result;
}

// Если нужно использовать SMTP, можно добавить функцию sendOrderEmailSMTP()
// Для этого потребуется библиотека PHPMailer или настройка SMTP в php.ini
?>
