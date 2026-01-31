const playwright = require('playwright');
const fs = require('fs');
const path = require('path');

async function main() {
  const products = JSON.parse(fs.readFileSync('vk_products.json', 'utf8'));
  const imagesDir = path.join(__dirname, 'vk_products_images');
  
  if (!fs.existsSync(imagesDir)) {
    fs.mkdirSync(imagesDir, { recursive: true });
  }
  
  const browser = await playwright.chromium.launch({ headless: false });
  const context = await browser.newContext({
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
  });
  const page = await context.newPage();
  
  // Переходим на страницу VK для установки cookies
  console.log('Opening VK page to establish session...');
  await page.goto('https://vk.com/market-200087933?display_albums=true', { waitUntil: 'networkidle' });
  await page.waitForTimeout(3000);
  
  const downloadedProducts = [];
  
  for (let i = 0; i < products.length; i++) {
    const product = products[i];
    console.log(`\n[${i + 1}/${products.length}] Processing: ${product.title}`);
    
    try {
      // Скачиваем изображение через fetch в контексте браузера
      const imageData = await page.evaluate(async (imageUrl) => {
        try {
          const response = await fetch(imageUrl, {
            method: 'GET',
            headers: {
              'Referer': 'https://vk.com/',
            }
          });
          
          if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
          }
          
          const blob = await response.blob();
          return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(blob);
          });
        } catch (error) {
          return { error: error.message };
        }
      }, product.imageUrl);
      
      if (imageData.error) {
        console.log(`  ✗ Error: ${imageData.error}`);
        continue;
      }
      
      // Конвертируем base64 в buffer
      const base64Data = imageData.replace(/^data:image\/\w+;base64,/, '');
      const buffer = Buffer.from(base64Data, 'base64');
      
      // Определяем расширение
      const url = new URL(product.imageUrl);
      const ext = path.extname(url.pathname) || '.jpg';
      const safeTitle = product.title.replace(/[^\w\s-]/g, '').substring(0, 50).replace(/[-\s]+/g, '-');
      const filename = `${String(i + 1).padStart(3, '0')}_${safeTitle}${ext}`;
      const filepath = path.join(imagesDir, filename);
      
      // Сохраняем файл
      fs.writeFileSync(filepath, buffer);
      
      if (fs.existsSync(filepath) && fs.statSync(filepath).size > 0) {
        product.localImage = `vk_products_images/${filename}`;
        downloadedProducts.push(product);
        console.log(`  ✓ Saved: ${filename} (${(buffer.length / 1024).toFixed(1)} KB)`);
      } else {
        console.log(`  ✗ File not saved properly`);
      }
      
    } catch (error) {
      console.log(`  ✗ Error: ${error.message}`);
    }
    
    // Задержка между запросами
    await page.waitForTimeout(1500);
  }
  
  // Сохраняем обновленные данные
  fs.writeFileSync(
    path.join(__dirname, 'vk_products.json'),
    JSON.stringify(downloadedProducts, null, 2),
    'utf8'
  );
  
  await browser.close();
  
  console.log(`\n✅ Complete! Downloaded ${downloadedProducts.length}/${products.length} images`);
  console.log(`Images saved in: ${imagesDir}`);
}

main().catch(console.error);
