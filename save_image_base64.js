const fs = require('fs');
const path = require('path');

// Функция для сохранения base64 изображения в файл
function saveBase64Image(base64Data, filename) {
  // Убираем префикс data:image если есть
  const base64 = base64Data.includes(',') ? base64Data.split(',')[1] : base64Data;
  
  // Декодируем base64
  const imageBuffer = Buffer.from(base64, 'base64');
  
  // Сохраняем файл
  const imagesDir = path.join(__dirname, 'vk_products_images');
  if (!fs.existsSync(imagesDir)) {
    fs.mkdirSync(imagesDir, { recursive: true });
  }
  
  const filepath = path.join(imagesDir, filename);
  fs.writeFileSync(filepath, imageBuffer);
  
  return filepath;
}

// Экспортируем функцию для использования
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { saveBase64Image };
}

// Если запускается напрямую, читаем из аргументов
if (require.main === module) {
  const args = process.argv.slice(2);
  if (args.length >= 2) {
    const base64Data = args[0];
    const filename = args[1];
    const savedPath = saveBase64Image(base64Data, filename);
    console.log(`Saved: ${savedPath}`);
  } else {
    console.log('Usage: node save_image_base64.js <base64_data> <filename>');
  }
}
