<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$contacts = loadContacts();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contacts = [
        'address' => trim($_POST['address'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'hours' => trim($_POST['hours'] ?? '')
    ];
    
    if (empty($contacts['address']) || empty($contacts['phone']) || empty($contacts['email'])) {
        $error = 'Заполните все обязательные поля';
    } else {
        saveContacts($contacts);
        $message = 'Контактная информация успешно обновлена';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление контактами</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-header">
        <div class="admin-container">
            <h1>Управление контактами</h1>
            <a href="dashboard.php" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    
    <div class="admin-container">
        <nav class="admin-nav">
            <a href="dashboard.php" class="nav-link">Главная</a>
            <a href="products.php" class="nav-link">Товары</a>
            <a href="services.php" class="nav-link">Услуги</a>
            <a href="orders.php" class="nav-link">Заказы</a>
            <a href="contacts.php" class="nav-link active">Контакты</a>
            <a href="seo.php" class="nav-link">SEO</a>
        </nav>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" class="contact-form">
            <div class="form-group">
                <label>Адрес *</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($contacts['address']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($contacts['phone']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($contacts['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Режим работы</label>
                <textarea name="hours" rows="3"><?php echo htmlspecialchars($contacts['hours']); ?></textarea>
                <small>Можно использовать HTML теги, например &lt;br&gt; для переноса строки</small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</body>
</html>
