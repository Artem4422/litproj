<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$products = loadProducts();
$action = $_GET['action'] ?? 'list';
$productId = $_GET['id'] ?? null;
$message = '';
$error = '';

// Обработка действий
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = trim($_POST['price'] ?? '');
        
        if (empty($name) || empty($price)) {
            $error = 'Заполните все обязательные поля';
        } else {
            $product = [
                'id' => $action === 'edit' ? intval($_POST['product_id']) : (time() + rand(1000, 9999)),
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'image' => $_POST['current_image'] ?? ''
            ];
            
            // Обработка загрузки изображения
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    // Убеждаемся, что директория существует
                    if (!file_exists(UPLOAD_DIR)) {
                        if (!mkdir(UPLOAD_DIR, 0777, true)) {
                            $error = 'Не удалось создать директорию для загрузки файлов. Проверьте права доступа.';
                        }
                    }
                    
                    // Проверяем права на запись
                    if (empty($error) && !is_writable(UPLOAD_DIR)) {
                        $error = 'Директория uploads/products/ недоступна для записи. Проверьте права доступа.';
                    }
                    
                    if (empty($error)) {
                        $filename = uniqid() . '.' . $ext;
                        $filepath = UPLOAD_DIR . $filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $filepath)) {
                            // Проверяем, что файл действительно загружен
                            if (file_exists($filepath)) {
                                // Удаляем старое изображение если редактируем (только локальные файлы)
                                if ($action === 'edit' && !empty($product['image'])) {
                                    $oldImagePath = $product['image'];
                                    // Проверяем, что это локальный файл, а не URL
                                    if (strpos($oldImagePath, 'http') !== 0 && file_exists(__DIR__ . '/../' . $oldImagePath)) {
                                        @unlink(__DIR__ . '/../' . $oldImagePath);
                                    }
                                }
                                $product['image'] = 'uploads/products/' . $filename;
                            } else {
                                $error = 'Файл не был сохранен. Проверьте права доступа.';
                            }
                        } else {
                            $error = 'Ошибка при перемещении загруженного файла. Проверьте права доступа к директории uploads/products/';
                        }
                    }
                } else {
                    $error = 'Недопустимый формат файла. Разрешены: JPG, PNG, GIF, WEBP';
                }
            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Обработка ошибок загрузки
                $uploadErrors = [
                    UPLOAD_ERR_INI_SIZE => 'Файл превышает максимальный размер, установленный в php.ini',
                    UPLOAD_ERR_FORM_SIZE => 'Файл превышает максимальный размер формы',
                    UPLOAD_ERR_PARTIAL => 'Файл был загружен частично',
                    UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная директория',
                    UPLOAD_ERR_CANT_WRITE => 'Ошибка записи файла на диск',
                    UPLOAD_ERR_EXTENSION => 'Загрузка файла остановлена расширением PHP'
                ];
                $error = $uploadErrors[$_FILES['image']['error']] ?? 'Неизвестная ошибка загрузки файла';
            }
            
            // Сохраняем товар только если нет ошибок
            if (empty($error)) {
                if ($action === 'add') {
                    $products[] = $product;
                    $message = 'Товар успешно добавлен';
                } else {
                    $index = array_search($product['id'], array_column($products, 'id'));
                    if ($index !== false) {
                        $products[$index] = $product;
                        $message = 'Товар успешно обновлен';
                    }
                }
                
                saveProducts($products);
                $products = loadProducts();
                $action = 'list';
            } else {
                // При ошибке оставляем форму открытой с данными товара
                $currentProduct = $product;
            }
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['product_id']);
        $index = array_search($id, array_column($products, 'id'));
        if ($index !== false) {
            // Удаляем изображение (только локальные файлы)
            if (!empty($products[$index]['image'])) {
                $imagePath = $products[$index]['image'];
                // Проверяем, что это локальный файл, а не URL
                if (strpos($imagePath, 'http') !== 0 && file_exists(__DIR__ . '/../' . $imagePath)) {
                    @unlink(__DIR__ . '/../' . $imagePath);
                }
            }
            unset($products[$index]);
            $products = array_values($products);
            saveProducts($products);
            $message = 'Товар успешно удален';
        }
        $action = 'list';
    }
}

$currentProduct = null;
if ($action === 'edit' && $productId) {
    $index = array_search(intval($productId), array_column($products, 'id'));
    if ($index !== false) {
        $currentProduct = $products[$index];
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
    <title>Управление товарами</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-header">
        <div class="admin-container">
            <h1>Управление товарами</h1>
            <a href="dashboard.php" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    
    <div class="admin-container">
        <nav class="admin-nav">
            <a href="dashboard.php" class="nav-link">Главная</a>
            <a href="products.php" class="nav-link active">Товары</a>
            <a href="services.php" class="nav-link">Услуги</a>
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
                <h2>Список товаров</h2>
                <a href="?action=add" class="btn btn-primary">Добавить товар</a>
            </div>
            
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Цена</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Товаров пока нет</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                                    <td>
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="" class="product-thumb">
                                        <?php else: ?>
                                            <span class="no-image">Нет фото</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 50)); ?>...</td>
                                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                                    <td>
                                        <a href="?action=edit&id=<?php echo $product['id']; ?>" class="btn btn-small btn-primary">Редактировать</a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Удалить товар?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
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
                <h2><?php echo $action === 'add' ? 'Добавить товар' : 'Редактировать товар'; ?></h2>
                <a href="products.php" class="btn btn-secondary">Назад к списку</a>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($currentProduct): ?>
                    <input type="hidden" name="product_id" value="<?php echo $currentProduct['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Название товара *</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($currentProduct['name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="5"><?php echo htmlspecialchars($currentProduct['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Цена *</label>
                    <input type="text" name="price" value="<?php echo htmlspecialchars($currentProduct['price'] ?? ''); ?>" required placeholder="1000 ₽">
                </div>
                
                <div class="form-group">
                    <label>Изображение</label>
                    <?php if (!empty($currentProduct['image'])): ?>
                        <div class="current-image">
                            <img src="../<?php echo htmlspecialchars($currentProduct['image']); ?>" alt="" style="max-width: 200px; display: block; margin-bottom: 10px;">
                            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($currentProduct['image']); ?>">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" id="imageInput" accept="image/*" onchange="handleFileSelect(this)">
                    <div id="fileInfo" style="margin-top: 10px; padding: 10px; background: #f0f0f0; border-radius: 4px; display: none;">
                        <strong>Выбранный файл:</strong> <span id="fileName"></span> (<span id="fileSize"></span>)
                    </div>
                    <small>Форматы: JPG, PNG, GIF, WEBP. Максимальный размер: 5MB</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Сохранить</button>
                    <a href="products.php" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
    
    <script>
        function handleFileSelect(input) {
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                
                fileName.textContent = file.name;
                fileSize.textContent = fileSizeMB + ' MB';
                fileInfo.style.display = 'block';
                
                // Проверка размера файла (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Файл слишком большой! Максимальный размер: 5MB');
                    input.value = '';
                    fileInfo.style.display = 'none';
                    return;
                }
                
                // Предпросмотр изображения
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('imagePreview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'imagePreview';
                        preview.style.maxWidth = '200px';
                        preview.style.display = 'block';
                        preview.style.marginTop = '10px';
                        preview.style.borderRadius = '4px';
                        input.parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                fileInfo.style.display = 'none';
                const preview = document.getElementById('imagePreview');
                if (preview) {
                    preview.remove();
                }
            }
        }
        
        // Проверка формы перед отправкой
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.product-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = document.getElementById('submitBtn');
                    const imageInput = document.getElementById('imageInput');
                    
                    // Проверка размера файла
                    if (imageInput && imageInput.files && imageInput.files[0]) {
                        const file = imageInput.files[0];
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        
                        if (file.size > maxSize) {
                            e.preventDefault();
                            alert('Файл слишком большой! Максимальный размер: 5MB');
                            return false;
                        }
                    }
                    
                    // Показываем индикатор загрузки
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Загрузка...';
                    }
                });
            }
        });
    </script>
</body>
</html>
