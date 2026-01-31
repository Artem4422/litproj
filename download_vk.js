const playwright = require('playwright');
const https = require('https');
const http = require('http');
const fs = require('fs');
const path = require('path');

async function downloadImage(url, filepath) {
  return new Promise((resolve, reject) => {
    const protocol = url.startsWith('https') ? https : http;
    const file = fs.createWriteStream(filepath);
    
    protocol.get(url, (response) => {
      if (response.statusCode === 301 || response.statusCode === 302) {
        // Редирект
        return downloadImage(response.headers.location, filepath).then(resolve).catch(reject);
      }
      
      response.pipe(file);
      file.on('finish', () => {
        file.close();
        resolve();
      });
    }).on('error', (err) => {
      fs.unlink(filepath, () => {});
      reject(err);
    });
  });
}

async function main() {
  const browser = await playwright.chromium.launch({ headless: false });
  const context = await browser.newContext({
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
  });
  const page = await context.newPage();
  
  try {
    console.log('Загрузка страницы VK...');
    await page.goto('https://vk.com/market-200087933?display_albums=true', { waitUntil: 'networkidle' });
    
    // Прокручиваем страницу вниз
    console.log('Прокрутка страницы...');
    await page.evaluate(async () => {
      await new Promise((resolve) => {
        let totalHeight = 0;
        const distance = 100;
        const timer = setInterval(() => {
          const scrollHeight = document.body.scrollHeight;
          window.scrollBy(0, distance);
          totalHeight += distance;
          
          if(totalHeight >= scrollHeight){
            clearInterval(timer);
            setTimeout(resolve, 2000);
          }
        }, 100);
      });
    });
    
    // Нажимаем "Показать ещё" если есть
    try {
      const showMoreButton = await page.$('button:has-text("Показать ещё"), [role="button"]:has-text("Показать ещё")');
      if (showMoreButton) {
        await showMoreButton.click();
        await page.waitForTimeout(3000);
      }
    } catch (e) {
      // Кнопка не найдена, продолжаем
    }
    
    // Извлекаем данные о товарах
    console.log('Извлечение данных о товарах...');
    const products = await page.evaluate(() => {
      const products = [];
      const productLinks = Array.from(document.querySelectorAll('a[href*="/market/product/"]'));
      const seenUrls = new Set();

      productLinks.forEach((link, index) => {
        const img = link.querySelector('img');
        if (img && img.src && !img.src.includes('data:') && !seenUrls.has(img.src)) {
          seenUrls.add(img.src);
          
          let title = `Товар ${index + 1}`;
          const titleLink = link.parentElement?.querySelector('a[href*="/market/product/"]') || 
                           link.nextElementSibling?.querySelector('a[href*="/market/product/"]');
          if (titleLink) {
            title = titleLink.textContent.trim();
          }
          
          let price = '';
          const parent = link.closest('div');
          if (parent) {
            const priceText = parent.textContent;
            const priceMatch = priceText.match(/(\d+\s*\d*\s*₽)/);
            if (priceMatch) {
              price = priceMatch[1];
            }
          }
          
          let imageUrl = img.src;
          if (imageUrl.startsWith('//')) {
            imageUrl = 'https:' + imageUrl;
          } else if (imageUrl.startsWith('/')) {
            imageUrl = 'https://vk.com' + imageUrl;
          }
          
          products.push({
            title: title,
            imageUrl: imageUrl,
            price: price,
            link: link.href
          });
        }
      });

      return products;
    });
    
    console.log(`Найдено товаров: ${products.length}`);
    
    // Создаем папку для изображений
    const imagesDir = path.join(__dirname, 'vk_products_images');
    if (!fs.existsSync(imagesDir)) {
      fs.mkdirSync(imagesDir, { recursive: true });
    }
    
    // Скачиваем изображения
    const productsData = [];
    for (let i = 0; i < products.length; i++) {
      const product = products[i];
      console.log(`Скачивание ${i + 1}/${products.length}: ${product.title}`);
      
      const url = new URL(product.imageUrl);
      const ext = path.extname(url.pathname) || '.jpg';
      const safeTitle = product.title.replace(/[^\w\s-]/g, '').substring(0, 50).replace(/[-\s]+/g, '-');
      const filename = `${String(i + 1).padStart(3, '0')}_${safeTitle}${ext}`;
      const filepath = path.join(imagesDir, filename);
      
      try {
        await downloadImage(product.imageUrl, filepath);
        product.localImage = `vk_products_images/${filename}`;
        productsData.push(product);
        await new Promise(resolve => setTimeout(resolve, 500)); // Задержка между запросами
      } catch (err) {
        console.error(`Ошибка при скачивании ${product.title}: ${err.message}`);
      }
    }
    
    // Сохраняем данные в JSON
    fs.writeFileSync(
      path.join(__dirname, 'vk_products.json'),
      JSON.stringify(productsData, null, 2),
      'utf8'
    );
    
    console.log(`\nГотово! Скачано ${productsData.length} изображений`);
    console.log(`Данные сохранены в vk_products.json`);
    
  } catch (error) {
    console.error('Ошибка:', error);
  } finally {
    await browser.close();
  }
}

main().catch(console.error);
