<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$seo = loadSEO();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seo = [
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'keywords' => trim($_POST['keywords'] ?? ''),
        'author' => trim($_POST['author'] ?? ''),
        'robots' => trim($_POST['robots'] ?? ''),
        'language' => trim($_POST['language'] ?? ''),
        'revisit_after' => trim($_POST['revisit_after'] ?? ''),
        'geo_region' => trim($_POST['geo_region'] ?? ''),
        'geo_placename' => trim($_POST['geo_placename'] ?? ''),
        'geo_position' => trim($_POST['geo_position'] ?? ''),
        'og_type' => trim($_POST['og_type'] ?? ''),
        'og_url' => trim($_POST['og_url'] ?? ''),
        'og_title' => trim($_POST['og_title'] ?? ''),
        'og_description' => trim($_POST['og_description'] ?? ''),
        'og_image' => trim($_POST['og_image'] ?? ''),
        'og_image_width' => trim($_POST['og_image_width'] ?? ''),
        'og_image_height' => trim($_POST['og_image_height'] ?? ''),
        'og_image_alt' => trim($_POST['og_image_alt'] ?? ''),
        'og_site_name' => trim($_POST['og_site_name'] ?? ''),
        'twitter_card' => trim($_POST['twitter_card'] ?? ''),
        'twitter_title' => trim($_POST['twitter_title'] ?? ''),
        'twitter_description' => trim($_POST['twitter_description'] ?? ''),
        'twitter_image' => trim($_POST['twitter_image'] ?? ''),
        'canonical_url' => trim($_POST['canonical_url'] ?? ''),
        'favicon' => trim($_POST['favicon'] ?? ''),
        'google_verification' => trim($_POST['google_verification'] ?? ''),
        'yandex_verification' => trim($_POST['yandex_verification'] ?? ''),
        'vk_verification' => trim($_POST['vk_verification'] ?? '')
    ];
    
    if (empty($seo['title']) || empty($seo['description'])) {
        $error = 'Заполните обязательные поля: Title и Description';
    } else {
        try {
            saveSEO($seo);
            $message = 'SEO настройки успешно сохранены';
        } catch (Exception $e) {
            $error = 'Ошибка сохранения: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO оптимизация</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .seo-section {
            background: var(--bg-card);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border: 1px solid rgba(157, 139, 111, 0.2);
        }
        .seo-section h3 {
            color: var(--gold);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(157, 139, 111, 0.3);
            font-size: 1.3rem;
        }
        .seo-help {
            background: rgba(157, 139, 111, 0.1);
            padding: 1rem;
            border-radius: 6px;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
            border-left: 3px solid var(--gold);
        }
        .char-count {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        .char-count.warning {
            color: #ff9800;
        }
        .char-count.error {
            color: #f44336;
        }
        .seo-preview {
            background: var(--bg-dark);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
            border: 1px solid rgba(157, 139, 111, 0.2);
        }
        .seo-preview h4 {
            color: var(--gold);
            margin-bottom: 1rem;
        }
        .preview-item {
            margin: 0.5rem 0;
            color: var(--text-light);
        }
        .preview-label {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="admin-container">
            <h1>SEO оптимизация</h1>
            <a href="dashboard.php" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    
    <div class="admin-container">
        <nav class="admin-nav">
            <a href="dashboard.php" class="nav-link">Главная</a>
            <a href="products.php" class="nav-link">Товары</a>
            <a href="services.php" class="nav-link">Услуги</a>
            <a href="orders.php" class="nav-link">Заказы</a>
            <a href="contacts.php" class="nav-link">Контакты</a>
            <a href="seo.php" class="nav-link active">SEO</a>
        </nav>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" class="product-form">
            <!-- Основные мета-теги -->
            <div class="seo-section">
                <h3>Основные мета-теги</h3>
                
                <div class="form-group">
                    <label>Title (Заголовок страницы) *</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($seo['title']); ?>" required maxlength="70">
                    <div class="char-count" id="title-count">0 / 70 символов (рекомендуется 50-60)</div>
                    <div class="seo-help">
                        <strong>Рекомендации:</strong> Используйте ключевые слова в начале. Длина 50-60 символов для лучшего отображения в поисковой выдаче.
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Description (Описание страницы) *</label>
                    <textarea name="description" id="description" rows="3" required maxlength="160"><?php echo htmlspecialchars($seo['description']); ?></textarea>
                    <div class="char-count" id="description-count">0 / 160 символов (рекомендуется 150-160)</div>
                    <div class="seo-help">
                        <strong>Рекомендации:</strong> Краткое описание содержимого страницы. Включите основные ключевые слова. Длина 150-160 символов.
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Keywords (Ключевые слова)</label>
                    <input type="text" name="keywords" value="<?php echo htmlspecialchars($seo['keywords']); ?>" placeholder="ключевое слово 1, ключевое слово 2, ключевое слово 3">
                    <div class="seo-help">
                        <strong>Примечание:</strong> Используйте запятые для разделения ключевых слов. Современные поисковые системы меньше полагаются на keywords, но они все еще полезны.
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Author (Автор)</label>
                    <input type="text" name="author" value="<?php echo htmlspecialchars($seo['author']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Robots (Индексация)</label>
                    <select name="robots">
                        <option value="index, follow" <?php echo $seo['robots'] === 'index, follow' ? 'selected' : ''; ?>>index, follow (Разрешить индексацию)</option>
                        <option value="noindex, nofollow" <?php echo $seo['robots'] === 'noindex, nofollow' ? 'selected' : ''; ?>>noindex, nofollow (Запретить индексацию)</option>
                        <option value="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" <?php echo strpos($seo['robots'], 'max-snippet') !== false ? 'selected' : ''; ?>>Расширенные настройки</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Language (Язык)</label>
                    <input type="text" name="language" value="<?php echo htmlspecialchars($seo['language']); ?>" placeholder="Russian">
                </div>
                
                <div class="form-group">
                    <label>Revisit After (Повторное посещение)</label>
                    <input type="text" name="revisit_after" value="<?php echo htmlspecialchars($seo['revisit_after']); ?>" placeholder="7 days">
                </div>
            </div>
            
            <!-- Геолокация -->
            <div class="seo-section">
                <h3>Геолокация</h3>
                
                <div class="form-group">
                    <label>Geo Region</label>
                    <input type="text" name="geo_region" value="<?php echo htmlspecialchars($seo['geo_region']); ?>" placeholder="RU-MOW">
                </div>
                
                <div class="form-group">
                    <label>Geo Placename</label>
                    <input type="text" name="geo_placename" value="<?php echo htmlspecialchars($seo['geo_placename']); ?>" placeholder="Москва">
                </div>
                
                <div class="form-group">
                    <label>Geo Position (широта;долгота)</label>
                    <input type="text" name="geo_position" value="<?php echo htmlspecialchars($seo['geo_position']); ?>" placeholder="55.7558;37.6173">
                </div>
            </div>
            
            <!-- Open Graph (Facebook, VK) -->
            <div class="seo-section">
                <h3>Open Graph (Facebook, VK, социальные сети)</h3>
                
                <div class="form-group">
                    <label>OG Type</label>
                    <select name="og_type">
                        <option value="website" <?php echo $seo['og_type'] === 'website' ? 'selected' : ''; ?>>website</option>
                        <option value="article" <?php echo $seo['og_type'] === 'article' ? 'selected' : ''; ?>>article</option>
                        <option value="business.business" <?php echo $seo['og_type'] === 'business.business' ? 'selected' : ''; ?>>business.business</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>OG URL</label>
                    <input type="url" name="og_url" value="<?php echo htmlspecialchars($seo['og_url']); ?>" placeholder="https://triol-foundry.ru/">
                </div>
                
                <div class="form-group">
                    <label>OG Title</label>
                    <input type="text" name="og_title" value="<?php echo htmlspecialchars($seo['og_title']); ?>" maxlength="60">
                    <div class="char-count" id="og-title-count">0 / 60 символов</div>
                </div>
                
                <div class="form-group">
                    <label>OG Description</label>
                    <textarea name="og_description" id="og-description" rows="3" maxlength="200"><?php echo htmlspecialchars($seo['og_description']); ?></textarea>
                    <div class="char-count" id="og-description-count">0 / 200 символов</div>
                </div>
                
                <div class="form-group">
                    <label>OG Image (URL изображения для соц. сетей)</label>
                    <input type="url" name="og_image" value="<?php echo htmlspecialchars($seo['og_image']); ?>" placeholder="https://triol-foundry.ru/AVA.jpg">
                    <div class="seo-help">
                        <strong>Рекомендации:</strong> Используйте изображение размером 1200x630px для лучшего отображения в социальных сетях.
                    </div>
                </div>
                
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>OG Image Width</label>
                        <input type="number" name="og_image_width" value="<?php echo htmlspecialchars($seo['og_image_width']); ?>" placeholder="1200">
                    </div>
                    <div class="form-group">
                        <label>OG Image Height</label>
                        <input type="number" name="og_image_height" value="<?php echo htmlspecialchars($seo['og_image_height']); ?>" placeholder="630">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>OG Image Alt</label>
                    <input type="text" name="og_image_alt" value="<?php echo htmlspecialchars($seo['og_image_alt']); ?>">
                </div>
                
                <div class="form-group">
                    <label>OG Site Name</label>
                    <input type="text" name="og_site_name" value="<?php echo htmlspecialchars($seo['og_site_name']); ?>">
                </div>
            </div>
            
            <!-- Twitter Card -->
            <div class="seo-section">
                <h3>Twitter Card</h3>
                
                <div class="form-group">
                    <label>Twitter Card Type</label>
                    <select name="twitter_card">
                        <option value="summary" <?php echo $seo['twitter_card'] === 'summary' ? 'selected' : ''; ?>>summary</option>
                        <option value="summary_large_image" <?php echo $seo['twitter_card'] === 'summary_large_image' ? 'selected' : ''; ?>>summary_large_image</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Twitter Title</label>
                    <input type="text" name="twitter_title" value="<?php echo htmlspecialchars($seo['twitter_title']); ?>" maxlength="70">
                </div>
                
                <div class="form-group">
                    <label>Twitter Description</label>
                    <textarea name="twitter_description" rows="2" maxlength="200"><?php echo htmlspecialchars($seo['twitter_description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Twitter Image</label>
                    <input type="url" name="twitter_image" value="<?php echo htmlspecialchars($seo['twitter_image']); ?>" placeholder="https://triol-foundry.ru/AVA.jpg">
                </div>
            </div>
            
            <!-- Верификация поисковых систем -->
            <div class="seo-section">
                <h3>Верификация поисковых систем</h3>
                
                <div class="form-group">
                    <label>Google Search Console Verification</label>
                    <input type="text" name="google_verification" value="<?php echo htmlspecialchars($seo['google_verification'] ?? ''); ?>" placeholder="Ваш код верификации Google">
                    <div class="seo-help">
                        <strong>Как получить:</strong> Зайдите в <a href="https://search.google.com/search-console" target="_blank">Google Search Console</a>, добавьте свой сайт и скопируйте код верификации из мета-тега.
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Yandex Webmaster Verification</label>
                    <input type="text" name="yandex_verification" value="<?php echo htmlspecialchars($seo['yandex_verification'] ?? ''); ?>" placeholder="Ваш код верификации Yandex">
                    <div class="seo-help">
                        <strong>Как получить:</strong> Зайдите в <a href="https://webmaster.yandex.ru" target="_blank">Яндекс.Вебмастер</a>, добавьте свой сайт и скопируйте код верификации.
                    </div>
                </div>
                
                <div class="form-group">
                    <label>VK Verification</label>
                    <input type="text" name="vk_verification" value="<?php echo htmlspecialchars($seo['vk_verification'] ?? ''); ?>" placeholder="Ваш код верификации VK">
                </div>
            </div>
            
            <!-- Дополнительные настройки -->
            <div class="seo-section">
                <h3>Дополнительные настройки</h3>
                
                <div class="form-group">
                    <label>Canonical URL</label>
                    <input type="url" name="canonical_url" value="<?php echo htmlspecialchars($seo['canonical_url']); ?>" placeholder="https://triol-foundry.ru/">
                    <div class="seo-help">
                        <strong>Примечание:</strong> Указывает канонический URL страницы для избежания дублирования контента.
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Favicon</label>
                    <input type="text" name="favicon" value="<?php echo htmlspecialchars($seo['favicon']); ?>" placeholder="/favicon.ico">
                </div>
            </div>
            
            <!-- Предпросмотр -->
            <div class="seo-section">
                <h3>Предпросмотр в поисковой выдаче</h3>
                <div class="seo-preview">
                    <h4>Как будет выглядеть ваш сайт в Google:</h4>
                    <div class="preview-item">
                        <div class="preview-label">Заголовок:</div>
                        <div id="preview-title" style="color: #1a0dab; font-size: 1.2rem; margin: 0.5rem 0;"><?php echo htmlspecialchars($seo['title']); ?></div>
                    </div>
                    <div class="preview-item">
                        <div class="preview-label">URL:</div>
                        <div style="color: #006621; font-size: 0.9rem; margin: 0.5rem 0;"><?php echo htmlspecialchars($seo['og_url']); ?></div>
                    </div>
                    <div class="preview-item">
                        <div class="preview-label">Описание:</div>
                        <div id="preview-description" style="color: #545454; font-size: 0.9rem; margin: 0.5rem 0; line-height: 1.4;"><?php echo htmlspecialchars($seo['description']); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить SEO настройки</button>
                <a href="dashboard.php" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
    
    <script>
        // Подсчет символов и обновление предпросмотра
        function updateCharCount(inputId, countId, maxChars, recommended) {
            const input = document.getElementById(inputId);
            const count = document.getElementById(countId);
            
            if (input && count) {
                input.addEventListener('input', function() {
                    const length = this.value.length;
                    count.textContent = length + ' / ' + maxChars + ' символов' + (recommended ? ' (рекомендуется ' + recommended + ')' : '');
                    
                    if (length > maxChars) {
                        count.className = 'char-count error';
                    } else if (length > recommended) {
                        count.className = 'char-count warning';
                    } else {
                        count.className = 'char-count';
                    }
                    
                    // Обновление предпросмотра
                    if (inputId === 'title') {
                        document.getElementById('preview-title').textContent = this.value || '<?php echo htmlspecialchars($seo['title']); ?>';
                    } else if (inputId === 'description') {
                        document.getElementById('preview-description').textContent = this.value || '<?php echo htmlspecialchars($seo['description']); ?>';
                    }
                });
                
                // Инициализация при загрузке
                input.dispatchEvent(new Event('input'));
            }
        }
        
        // Инициализация счетчиков
        document.addEventListener('DOMContentLoaded', function() {
            updateCharCount('title', 'title-count', 70, '50-60');
            updateCharCount('description', 'description-count', 160, '150-160');
            updateCharCount('og-title', 'og-title-count', 60, null);
            updateCharCount('og-description', 'og-description-count', 200, null);
        });
    </script>
</body>
</html>
