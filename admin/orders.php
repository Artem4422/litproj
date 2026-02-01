<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$orders = loadOrders();
$services = loadServices();
$action = $_GET['action'] ?? 'list';
$orderId = $_GET['id'] ?? null;
$message = '';
$error = '';

// Обработка действий
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'delete') {
        $id = intval($_POST['order_id']);
        $index = array_search($id, array_column($orders, 'id'));
        if ($index !== false) {
            unset($orders[$index]);
            $orders = array_values($orders);
            saveOrders($orders);
            $message = 'Заказ успешно удален';
        }
        $action = 'list';
    } elseif ($action === 'mark_read') {
        $id = intval($_POST['order_id']);
        $index = array_search($id, array_column($orders, 'id'));
        if ($index !== false) {
            $orders[$index]['read'] = true;
            saveOrders($orders);
            $message = 'Заказ отмечен как прочитанный';
        }
        $action = 'list';
    }
}

// Сортировка заказов по дате (новые сначала)
usort($orders, function($a, $b) {
    return ($b['created_at'] ?? 0) - ($a['created_at'] ?? 0);
});

$currentOrder = null;
if ($action === 'view' && $orderId) {
    $index = array_search(intval($orderId), array_column($orders, 'id'));
    if ($index !== false) {
        $currentOrder = $orders[$index];
        // Отмечаем как прочитанный
        if (empty($currentOrder['read'])) {
            $orders[$index]['read'] = true;
            saveOrders($orders);
        }
    } else {
        $action = 'list';
    }
}

// Подсчет статистики
$totalOrders = count($orders);
$unreadOrders = count(array_filter($orders, function($order) {
    return empty($order['read']);
}));
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказы услуг</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .order-status {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .order-status.new {
            background: #e74c3c;
            color: white;
        }
        .order-status.read {
            background: #95a5a6;
            color: white;
        }
        .order-details {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-top: 1rem;
        }
        .order-detail-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        .order-detail-row:last-child {
            border-bottom: none;
        }
        .order-detail-label {
            font-weight: bold;
            width: 200px;
            color: #555;
        }
        .order-detail-value {
            flex: 1;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="admin-container">
            <h1>Заказы услуг</h1>
            <a href="dashboard.php" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    
    <div class="admin-container">
        <nav class="admin-nav">
            <a href="dashboard.php" class="nav-link">Главная</a>
            <a href="products.php" class="nav-link">Товары</a>
            <a href="services.php" class="nav-link">Услуги</a>
            <a href="orders.php" class="nav-link active">Заказы</a>
            <a href="contacts.php" class="nav-link">Контакты</a>
            <a href="seo.php" class="nav-link">SEO</a>
        </nav>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($action === 'list'): ?>
            <div class="page-header">
                <h2>Список заказов</h2>
                <div>
                    <span style="margin-right: 1rem;">Всего: <strong><?php echo $totalOrders; ?></strong></span>
                    <span style="color: #e74c3c;">Новых: <strong><?php echo $unreadOrders; ?></strong></span>
                </div>
            </div>
            
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата</th>
                            <th>Тип услуги</th>
                            <th>Имя</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Заказов пока нет</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <?php
                                $serviceName = 'Не указано';
                                foreach ($services as $service) {
                                    if ($service['id'] == $order['service_type']) {
                                        $serviceName = $service['name'];
                                        break;
                                    }
                                }
                                ?>
                                <tr style="<?php echo empty($order['read']) ? 'background: #fff3cd;' : ''; ?>">
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo date('d.m.Y H:i', $order['created_at'] ?? time()); ?></td>
                                    <td><?php echo htmlspecialchars($serviceName); ?></td>
                                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                                    <td>
                                        <?php if (empty($order['read'])): ?>
                                            <span class="order-status new">Новый</span>
                                        <?php else: ?>
                                            <span class="order-status read">Прочитан</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="?action=view&id=<?php echo $order['id']; ?>" class="btn btn-small btn-primary">Просмотр</a>
                                        <?php if (empty($order['read'])): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="mark_read">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <button type="submit" class="btn btn-small btn-secondary">Отметить прочитанным</button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Удалить заказ?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <button type="submit" class="btn btn-small btn-danger">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        
        <?php elseif ($action === 'view' && $currentOrder): ?>
            <div class="page-header">
                <h2>Заказ #<?php echo htmlspecialchars($currentOrder['id']); ?></h2>
                <a href="orders.php" class="btn btn-secondary">Назад к списку</a>
            </div>
            
            <?php
            $serviceName = 'Не указано';
            foreach ($services as $service) {
                if ($service['id'] == $currentOrder['service_type']) {
                    $serviceName = $service['name'];
                    break;
                }
            }
            ?>
            
            <div class="order-details">
                <div class="order-detail-row">
                    <div class="order-detail-label">Дата заказа:</div>
                    <div class="order-detail-value"><?php echo date('d.m.Y H:i', $currentOrder['created_at'] ?? time()); ?></div>
                </div>
                <div class="order-detail-row">
                    <div class="order-detail-label">Тип услуги:</div>
                    <div class="order-detail-value"><?php echo htmlspecialchars($serviceName); ?></div>
                </div>
                <div class="order-detail-row">
                    <div class="order-detail-label">Имя:</div>
                    <div class="order-detail-value"><?php echo htmlspecialchars($currentOrder['name']); ?></div>
                </div>
                <div class="order-detail-row">
                    <div class="order-detail-label">Телефон:</div>
                    <div class="order-detail-value">
                        <a href="tel:<?php echo htmlspecialchars($currentOrder['phone']); ?>">
                            <?php echo htmlspecialchars($currentOrder['phone']); ?>
                        </a>
                    </div>
                </div>
                <div class="order-detail-row">
                    <div class="order-detail-label">Email:</div>
                    <div class="order-detail-value">
                        <a href="mailto:<?php echo htmlspecialchars($currentOrder['email']); ?>">
                            <?php echo htmlspecialchars($currentOrder['email']); ?>
                        </a>
                    </div>
                </div>
                <div class="order-detail-row">
                    <div class="order-detail-label">Описание заказа:</div>
                    <div class="order-detail-value"><?php echo nl2br(htmlspecialchars($currentOrder['description'])); ?></div>
                </div>
                <?php if (!empty($currentOrder['deadline'])): ?>
                    <div class="order-detail-row">
                        <div class="order-detail-label">Желаемые сроки:</div>
                        <div class="order-detail-value"><?php echo htmlspecialchars($currentOrder['deadline']); ?></div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-actions" style="margin-top: 1rem;">
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="mark_read">
                    <input type="hidden" name="order_id" value="<?php echo $currentOrder['id']; ?>">
                    <button type="submit" class="btn btn-secondary">Отметить прочитанным</button>
                </form>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Удалить заказ?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="order_id" value="<?php echo $currentOrder['id']; ?>">
                    <button type="submit" class="btn btn-danger">Удалить заказ</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
