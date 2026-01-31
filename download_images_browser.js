const playwright = require('playwright');
const fs = require('fs');
const path = require('path');

async function downloadImage(page, imageUrl, filepath) {
  try {
    const response = await page.goto(imageUrl, { waitUntil: 'networkidle' });
    if (response && response.ok()) {
      const buffer = await response.body();
      fs.writeFileSync(filepath, buffer);
      return true;
    }
  } catch (error) {
    console.error(`Error downloading ${imageUrl}:`, error.message);
    // Попробуем через fetch в браузере
    try {
      const base64 = await page.evaluate(async (url) => {
        const response = await fetch(url);
        const blob = await response.blob();
        return new Promise((resolve) => {
          const reader = new FileReader();
          reader.onloadend = () => resolve(reader.result);
          reader.readAsDataURL(blob);
        });
      }, imageUrl);
      
      // Конвертируем base64 в buffer
      const base64Data = base64.replace(/^data:image\/\w+;base64,/, '');
      const buffer = Buffer.from(base64Data, 'base64');
      fs.writeFileSync(filepath, buffer);
      return true;
    } catch (e) {
      console.error(`Alternative method failed:`, e.message);
      return false;
    }
  }
  return false;
}

async function main() {
  // Читаем JSON с товарами
  const products = JSON.parse(fs.readFileSync('vk_products.json', 'utf8'));
  
  // Создаем папку для изображений
  const imagesDir = path.join(__dirname, 'vk_products_images');
  if (!fs.existsSync(imagesDir)) {
    fs.mkdirSync(imagesDir, { recursive: true });
  }
  
  const browser = await playwright.chromium.launch({ headless: false });
  const context = await browser.newContext({
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
  });
  const page = await context.newPage();
  
  // Сначала переходим на главную страницу VK для установки cookies
  await page.goto('https://vk.com/market-200087933?display_albums=true', { waitUntil: 'networkidle' });
  await page.waitForTimeout(2000);
  
  const downloadedProducts = [];
  
  for (let i = 0; i < products.length; i++) {
    const product = products[i];
    console.log(`Downloading ${i + 1}/${products.length}: ${product.title}`);
    
    // Определяем расширение файла
    const url = new URL(product.imageUrl);
    const ext = path.extname(url.pathname) || '.jpg';
    
    // Создаем безопасное имя файла
    const safeTitle = product.title.replace(/[^\w\s-]/g, '').substring(0, 50).replace(/[-\s]+/g, '-');
    const filename = `${String(i + 1).padStart(3, '0')}_${safeTitle}${ext}`;
    const filepath = path.join(imagesDir, filename);
    
    // Скачиваем изображение
    const success = await downloadImage(page, product.imageUrl, filepath);
    
    if (success && fs.existsSync(filepath) && fs.statSync(filepath).size > 0) {
      product.localImage = `vk_products_images/${filename}`;
      downloadedProducts.push(product);
      console.log(`✓ Saved: ${filename}`);
    } else {
      console.log(`✗ Failed: ${product.title}`);
    }
    
    // Небольшая задержка между запросами
    await page.waitForTimeout(1000);
  }
  
  // Сохраняем обновленные данные
  fs.writeFileSync(
    path.join(__dirname, 'vk_products.json'),
    JSON.stringify(downloadedProducts, null, 2),
    'utf8'
  );
  
  await browser.close();
  
  console.log(`\nDone! Downloaded ${downloadedProducts.length} images`);
}

main().catch(console.error);
