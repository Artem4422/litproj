import requests
from bs4 import BeautifulSoup
import json
import os
import re
from urllib.parse import urlparse
import time

def download_image(url, filename):
    """Скачивает изображение по URL"""
    try:
        headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        }
        response = requests.get(url, headers=headers, timeout=30)
        if response.status_code == 200:
            with open(filename, 'wb') as f:
                f.write(response.content)
            return True
    except Exception as e:
        print(f"Ошибка при скачивании {url}: {e}")
    return False

def extract_products_from_page(html_content):
    """Извлекает информацию о товарах из HTML страницы"""
    soup = BeautifulSoup(html_content, 'html.parser')
    products = []
    
    # Ищем все ссылки на товары
    product_links = soup.find_all('a', href=re.compile(r'/market/product/'))
    
    seen_images = set()
    
    for link in product_links:
        img = link.find('img')
        if img and img.get('src'):
            img_url = img.get('src')
            
            # Пропускаем дубликаты
            if img_url in seen_images:
                continue
            seen_images.add(img_url)
            
            # Получаем полный URL изображения
            if img_url.startswith('//'):
                img_url = 'https:' + img_url
            elif img_url.startswith('/'):
                img_url = 'https://vk.com' + img_url
            
            # Извлекаем название товара
            title = "Товар"
            title_elem = link.find_next('a', href=re.compile(r'/market/product/'))
            if title_elem:
                title = title_elem.get_text(strip=True)
            else:
                # Пробуем найти название в родительском элементе
                parent = link.find_parent()
                if parent:
                    title_elem = parent.find('a', href=re.compile(r'/market/product/'))
                    if title_elem:
                        title = title_elem.get_text(strip=True)
            
            # Извлекаем цену
            price = ""
            price_elem = link.find_next(string=re.compile(r'₽'))
            if price_elem:
                price = price_elem.strip()
            else:
                # Ищем цену в родительском элементе
                parent = link.find_parent()
                if parent:
                    price_text = parent.get_text()
                    price_match = re.search(r'(\d+\s*\d*\s*₽)', price_text)
                    if price_match:
                        price = price_match.group(1)
            
            products.append({
                'title': title,
                'imageUrl': img_url,
                'price': price,
                'link': link.get('href', '')
            })
    
    return products

def main():
    url = 'https://vk.com/market-200087933?display_albums=true'
    
    print("Загрузка страницы VK...")
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language': 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7'
    }
    
    try:
        response = requests.get(url, headers=headers, timeout=30)
        response.raise_for_status()
        
        print("Извлечение информации о товарах...")
        products = extract_products_from_page(response.text)
        
        print(f"Найдено товаров: {len(products)}")
        
        # Создаем папку для изображений
        images_dir = 'vk_products_images'
        os.makedirs(images_dir, exist_ok=True)
        
        # Сохраняем информацию о товарах
        products_data = []
        
        # Скачиваем изображения
        for i, product in enumerate(products, 1):
            print(f"Скачивание {i}/{len(products)}: {product['title']}")
            
            # Определяем расширение файла
            parsed_url = urlparse(product['imageUrl'])
            ext = os.path.splitext(parsed_url.path)[1] or '.jpg'
            
            # Создаем безопасное имя файла
            safe_title = re.sub(r'[^\w\s-]', '', product['title'])[:50]
            safe_title = re.sub(r'[-\s]+', '-', safe_title)
            filename = f"{i:03d}_{safe_title}{ext}"
            filepath = os.path.join(images_dir, filename)
            
            if download_image(product['imageUrl'], filepath):
                product['localImage'] = filepath
                products_data.append(product)
                time.sleep(0.5)  # Небольшая задержка между запросами
            else:
                print(f"Не удалось скачать изображение для {product['title']}")
        
        # Сохраняем данные о товарах в JSON
        with open('vk_products.json', 'w', encoding='utf-8') as f:
            json.dump(products_data, f, ensure_ascii=False, indent=2)
        
        print(f"\nГотово! Скачано {len(products_data)} изображений")
        print(f"Данные сохранены в vk_products.json")
        
    except Exception as e:
        print(f"Ошибка: {e}")
        import traceback
        traceback.print_exc()

if __name__ == '__main__':
    main()
