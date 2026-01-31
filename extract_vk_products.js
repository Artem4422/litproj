// Скрипт для извлечения товаров со страницы VK
(async () => {
  // Прокручиваем страницу вниз для загрузки всех товаров
  await new Promise((resolve) => {
    let totalHeight = 0;
    const distance = 100;
    const timer = setInterval(() => {
      const scrollHeight = document.body.scrollHeight;
      window.scrollBy(0, distance);
      totalHeight += distance;
      
      if(totalHeight >= scrollHeight){
        clearInterval(timer);
        setTimeout(resolve, 2000); // Ждем загрузки контента
      }
    }, 100);
  });

  // Нажимаем "Показать ещё" если есть
  let showMoreButton = document.querySelector('[class*="show"], [class*="more"], button:contains("Показать")');
  if (!showMoreButton) {
    const buttons = Array.from(document.querySelectorAll('button, [role="button"]'));
    showMoreButton = buttons.find(btn => btn.textContent.includes('Показать ещё') || btn.textContent.includes('ещё'));
  }
  
  if (showMoreButton) {
    showMoreButton.click();
    await new Promise(resolve => setTimeout(resolve, 3000));
  }

  // Извлекаем данные о товарах
  const products = [];
  const productLinks = Array.from(document.querySelectorAll('a[href*="/market/product/"]'));
  const seenUrls = new Set();

  productLinks.forEach((link, index) => {
    const img = link.querySelector('img');
    if (img && img.src && !img.src.includes('data:') && !seenUrls.has(img.src)) {
      seenUrls.add(img.src);
      
      // Находим название товара
      let title = `Товар ${index + 1}`;
      const titleLink = link.parentElement?.querySelector('a[href*="/market/product/"]') || 
                       link.nextElementSibling?.querySelector('a[href*="/market/product/"]');
      if (titleLink) {
        title = titleLink.textContent.trim();
      }
      
      // Находим цену
      let price = '';
      const parent = link.closest('div');
      if (parent) {
        const priceText = parent.textContent;
        const priceMatch = priceText.match(/(\d+\s*\d*\s*₽)/);
        if (priceMatch) {
          price = priceMatch[1];
        }
      }
      
      // Получаем полный URL изображения
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
})();
