import json
import os
import base64
import re

# Читаем товары
with open('vk_products.json', 'r', encoding='utf-8') as f:
    products = json.load(f)

# Создаем папку для изображений
images_dir = 'vk_products_images'
os.makedirs(images_dir, exist_ok=True)

# Читаем скачанное изображение из файла
try:
    with open(r'C:\Users\artem\.cursor\projects\d-project-ZAKAZ\agent-tools\88a64c3d-a915-432e-8913-510b67ee46a4.txt', 'r', encoding='utf-8') as f:
        content = f.read()
        # Извлекаем base64 данные
        if 'base64' in content:
            # Находим base64 строку
            match = re.search(r'"base64":\s*"([^"]+)"', content)
            if match:
                base64_data = match.group(1)
                # Убираем префикс data:image если есть
                if ',' in base64_data:
                    base64_data = base64_data.split(',')[1]
                
                # Сохраняем первое изображение
                image_data = base64.b64decode(base64_data)
                filename = '001_Brelok-Chernaya-metka.jpg'
                filepath = os.path.join(images_dir, filename)
                with open(filepath, 'wb') as img_file:
                    img_file.write(image_data)
                print(f'Saved: {filename}')
                
                # Обновляем первый товар
                if len(products) > 0:
                    products[0]['localImage'] = f'vk_products_images/{filename}'
except Exception as e:
    print(f'Error reading image file: {e}')

# Сохраняем обновленные данные
with open('vk_products.json', 'w', encoding='utf-8') as f:
    json.dump(products, f, ensure_ascii=False, indent=2)

print(f'\nProcessed 1/{len(products)} images')
print('Note: To download all images, you need to open each product page in browser')
print('and get the large image URL, then download it through browser context.')
