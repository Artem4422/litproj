const fs = require('fs');
const path = require('path');

// Читаем JSON с товарами
const products = JSON.parse(fs.readFileSync('vk_products.json', 'utf8'));

// Этот скрипт будет выполняться в браузере через browser_evaluate
// для скачивания изображений с правильными заголовками

const downloadScript = `
(async () => {
  const products = ${JSON.stringify(products)};
  const results = [];
  
  for (const product of products) {
    try {
      const response = await fetch(product.imageUrl, {
        method: 'GET',
        headers: {
          'Referer': 'https://vk.com/',
          'User-Agent': navigator.userAgent
        }
      });
      
      if (response.ok) {
        const blob = await response.blob();
        const reader = new FileReader();
        const base64 = await new Promise((resolve) => {
          reader.onloadend = () => resolve(reader.result);
          reader.readAsDataURL(blob);
        });
        results.push({
          title: product.title,
          imageBase64: base64,
          success: true
        });
      } else {
        results.push({
          title: product.title,
          success: false,
          error: response.status
        });
      }
    } catch (error) {
      results.push({
        title: product.title,
        success: false,
        error: error.message
      });
    }
  }
  
  return results;
})();
`;

console.log('Скрипт для выполнения в браузере готов');
console.log('Используйте browser_evaluate с этим кодом для скачивания изображений');
