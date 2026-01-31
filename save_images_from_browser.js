const fs = require('fs');
const path = require('path');

// Читаем товары
const products = JSON.parse(fs.readFileSync('vk_products.json', 'utf8'));

// Создаем папку для изображений
const imagesDir = path.join(__dirname, 'vk_products_images');
if (!fs.existsSync(imagesDir)) {
  fs.mkdirSync(imagesDir, { recursive: true });
}

// Этот скрипт будет выполняться в браузере через browser_evaluate
// Он скачает все изображения и вернет их как base64
const downloadScript = `
(async () => {
  const products = ${JSON.stringify(products)};
  const results = [];
  
  for (let i = 0; i < products.length; i++) {
    const product = products[i];
    try {
      console.log('Downloading ' + (i + 1) + '/' + products.length + ': ' + product.title);
      
      const response = await fetch(product.imageUrl, {
        method: 'GET',
        headers: {
          'Referer': 'https://vk.com/',
        }
      });
      
      if (!response.ok) {
        results.push({
          index: i,
          title: product.title,
          success: false,
          error: 'HTTP ' + response.status
        });
        continue;
      }
      
      const blob = await response.blob();
      const base64 = await new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(blob);
      });
      
      results.push({
        index: i,
        title: product.title,
        imageUrl: product.imageUrl,
        base64: base64,
        success: true
      });
      
      // Небольшая задержка между запросами
      await new Promise(resolve => setTimeout(resolve, 500));
    } catch (error) {
      results.push({
        index: i,
        title: product.title,
        success: false,
        error: error.message
      });
    }
  }
  
  return results;
})();
`;

console.log('Download script ready. Use browser_evaluate with this code.');
console.log('Script length:', downloadScript.length);

// Сохраняем скрипт для использования
fs.writeFileSync('download_script_browser.js', downloadScript);
