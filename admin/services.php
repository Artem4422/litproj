<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$services = loadServices();
$action = $_GET['action'] ?? 'list';
$serviceId = $_GET['id'] ?? null;
$message = '';
$error = '';

// Обработка действий
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name'] ?? '');
        $active = isset($_POST['active']) ? 1 : 0;
        
        if (empty($name)) {
            $error = 'Введите название услуги';
        } else {
            $service = [
                'id' => $action === 'edit' ? intval($_POST['service_id']) : (time() + rand(1000, 9999)),
                'name' => $name,
                'active' => $active
            ];
            
            if ($action === 'add') {
                $services[] = $service;
                $message = 'Услуга успешно добавлена';
            } else {
                $index = array_search($service['id'], array_column($services, 'id'));
                if ($index !== false) {
                    $services[$index] = $service;
                    $message = 'Услуга успешно обновлена';
                }
            }
            
            saveServices($services);
            $services = loadServices();
            $action = 'list';
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['service_id']);
        $index = array_search($id, array_column($services, 'id'));
        if ($index !== false) {
            unset($services[$index]);
            $services = array_values($services);
            saveServices($services);
            $message = 'Услуга успешно удалена';
        }
        $action = 'list';
    } elseif ($action === 'toggle') {
        $id = intval($_POST['service_id']);
        $index = array_search($id, array_column($services, 'id'));
        if ($index !== false) {
            $services[$index]['active'] = $services[$index]['active'] ? 0 : 1;
            saveServices($services);
            $message = 'Статус услуги изменен';
        }
        $action = 'list';
    }
}

$currentService = null;
if ($action === 'edit' && $serviceId) {
    $index = array_search(intval($serviceId), array_column($services, 'id'));
    if ($index !== false) {
        $currentService = $services[$index];
    } else {
        $action = 'list';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление услугами</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-header">
        <div class="admin-container">
            <h1>Управление услугами</h1>
            <a href="dashboard.php" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    
    <div class="admin-container">
        <nav class="admin-nav">
            <a href="dashboard.php" class="nav-link">Главная</a>
            <a href="products.php" class="nav-link">Товары</a>
            <a href="services.php" class="nav-link active">Услуги</a>
            <a href="orders.php" class="nav-link">Заказы</a>
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
                <h2>Список услуг</h2>
                <a href="?action=add" class="btn btn-primary">Добавить услугу</a>
            </div>
            
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($services)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Услуг пока нет</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($service['id']); ?></td>
                                    <td><?php echo htmlspecialchars($service['name']); ?></td>
                                    <td>
                                        <?php if ($service['active']): ?>
                                            <span style="color: green;">Активна</span>
                                        <?php else: ?>
                                            <span style="color: red;">Неактивна</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="?action=edit&id=<?php echo $service['id']; ?>" class="btn btn-small btn-primary">Редактировать</a>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="toggle">
                                            <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                            <button type="submit" class="btn btn-small btn-secondary">
                                                <?php echo $service['active'] ? 'Деактивировать' : 'Активировать'; ?>
                                            </button>
                                        </form>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Удалить услугу?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                            <button type="submit" class="btn btn-small btn-danger">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        
        <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <div class="page-header">
                <h2><?php echo $action === 'add' ? 'Добавить услугу' : 'Редактировать услугу'; ?></h2>
                <a href="services.php" class="btn btn-secondary">Назад к списку</a>
            </div>
            
            <form method="POST" class="product-form">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($currentService): ?>
                    <input type="hidden" name="service_id" value="<?php echo $currentService['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Название услуги *</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($currentService['name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="active" <?php echo (!isset($currentService) || $currentService['active']) ? 'checked' : ''; ?>>
                        Активна (отображается в форме заказа)
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="services.php" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
