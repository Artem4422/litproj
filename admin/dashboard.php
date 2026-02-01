<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$products = loadProducts();
$contacts = loadContacts();
$orders = loadOrders();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Главная</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-header">
        <div class="admin-container">
            <h1>Админ-панель</h1>
            <a href="logout.php" class="btn btn-secondary">Выйти</a>
        </div>
    </div>
    
    <div class="admin-container">
        <nav class="admin-nav">
            <a href="dashboard.php" class="nav-link active">Главная</a>
            <a href="products.php" class="nav-link">Товары</a>
            <a href="services.php" class="nav-link">Услуги</a>
            <a href="orders.php" class="nav-link">Заказы</a>
            <a href="contacts.php" class="nav-link">Контакты</a>
            <a href="seo.php" class="nav-link">SEO</a>
        </nav>
        
        <?php
        $unreadOrders = count(array_filter($orders, function($order) {
            return empty($order['read']);
        }));
        ?>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Всего товаров</h3>
                <p class="stat-number"><?php echo count($products); ?></p>
            </div>
            <div class="stat-card">
                <h3>Новых заказов</h3>
                <p class="stat-number" style="color: <?php echo $unreadOrders > 0 ? '#e74c3c' : '#27ae60'; ?>;">
                    <?php echo $unreadOrders; ?>
                </p>
            </div>
            <div class="stat-card">
                <h3>Контакты</h3>
                <p class="stat-text"><?php echo htmlspecialchars($contacts['phone']); ?></p>
            </div>
        </div>
        
        <div class="quick-actions">
            <a href="products.php?action=add" class="btn btn-primary">Добавить товар</a>
            <a href="services.php" class="btn btn-primary">Управление услугами</a>
            <a href="orders.php" class="btn btn-primary">Просмотр заказов</a>
            <a href="contacts.php" class="btn btn-secondary">Изменить контакты</a>
        </div>
    </div>
</body>
</html>
