const fs = require('fs');
const path = require('path');

// Читаем base64 данные из большого файла
const filePath = 'C:\\Users\\artem\\.cursor\\projects\\d-project-ZAKAZ\\agent-tools\\88a64c3d-a915-432e-8913-510b67ee46a4.txt';

try {
  const content = fs.readFileSync(filePath, 'utf8');
  
  // Ищем base64 данные в JSON формате
  const jsonMatch = content.match(/"base64":\s*"([^"]+)"/);
  if (jsonMatch) {
    let base64Data = jsonMatch[1];
    
    // Убираем экранирование если есть
    base64Data = base64Data.replace(/\\n/g, '').replace(/\\/g, '');
    
    // Убираем префикс data:image если есть
    if (base64Data.includes(',')) {
      base64Data = base64Data.split(',')[1];
    }
    
    // Декодируем и сохраняем
    const imageBuffer = Buffer.from(base64Data, 'base64');
    
    const imagesDir = path.join(__dirname, 'vk_products_images');
    if (!fs.existsSync(imagesDir)) {
      fs.mkdirSync(imagesDir, { recursive: true });
    }
    
    const filename = '001_Brelok-Chernaya-metka.jpg';
    const filepath = path.join(imagesDir, filename);
    fs.writeFileSync(filepath, imageBuffer);
    
    console.log(`✓ Saved: ${filename} (${(imageBuffer.length / 1024).toFixed(1)} KB)`);
    
    // Обновляем JSON (убираем BOM если есть)
    let jsonContent = fs.readFileSync('vk_products.json', 'utf8');
    if (jsonContent.charCodeAt(0) === 0xFEFF) {
      jsonContent = jsonContent.slice(1);
    }
    const products = JSON.parse(jsonContent);
    if (products.length > 0) {
      products[0].localImage = `vk_products_images/${filename}`;
      fs.writeFileSync('vk_products.json', JSON.stringify(products, null, 2), 'utf8');
    }
  } else {
    console.log('Base64 data not found in file');
  }
} catch (error) {
  console.error('Error:', error.message);
}
