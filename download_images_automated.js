// Этот скрипт нужно выполнить через browser_evaluate
// Он скачает все изображения товаров через браузер

const products = [
  {"title": "Брелок \"Черная метка\"", "link": "https://vk.com/market/product/brelok-quotchernaya-metkaquot-200087933-12810411"},
  {"title": "Кулон - подвеска \" Чужой\"", "link": "https://vk.com/market/product/kulon-podveska-quot-chuzhoyquot-200087933-12810402"},
  {"title": "Статуэтка \"Кот ученый\"", "link": "https://vk.com/market/product/statuetka-quotkot-uchenyquot-200087933-12810357"},
  {"title": "Брелок \" Кот ученый\"", "link": "https://vk.com/market/product/brelok-quot-kot-uchenyquot-200087933-12810335"},
  {"title": "Бусина \"Кот ученый\"", "link": "https://vk.com/market/product/busina-quotkot-uchenyquot-200087933-12810333"},
  {"title": "Пряжка ременная \"Полемика\".", "link": "https://vk.com/market/product/pryazhka-remennaya-quotpolemikaquot-200087933-12274112"},
  {"title": "Пряжка ременная \" Ворон\"", "link": "https://vk.com/market/product/pryazhka-remennaya-quot-voronquot-200087933-12201242"},
  {"title": "Брелок - монета \" Золотой дублон\"", "link": "https://vk.com/market/product/brelok-moneta-quot-zolotoy-dublonquot-200087933-10918293"},
  {"title": "Пряжка \"Харлей Дэвидсон\"", "link": "https://vk.com/market/product/pryazhka-quotkharley-devidsonquot-200087933-10748273"},
  {"title": "Пряжка \"Эдди\"", "link": "https://vk.com/market/product/pryazhka-quoteddiquot-200087933-10747107"},
  {"title": "Пряжка \"Витязь\"", "link": "https://vk.com/market/product/pryazhka-quotvityazquot-200087933-10707097"},
  {"title": "Пряжка \"Оззи\"", "link": "https://vk.com/market/product/pryazhka-quotozziquot-200087933-10707068"},
  {"title": "Пряжка ременная \"Один в поле воин\"", "link": "https://vk.com/market/product/pryazhka-remennaya-quotodin-v-pole-voinquot-200087933-10398697"},
  {"title": "Пряжка \"Оззи\"", "link": "https://vk.com/market/product/pryazhka-quotozziquot-200087933-10381228"},
  {"title": "Пряжка \"Один в поле воин\"", "link": "https://vk.com/market/product/pryazhka-quotodin-v-pole-voinquot-200087933-10380318"},
  {"title": "Пряжка \"Харлей Дэвидсон\"", "link": "https://vk.com/market/product/pryazhka-quotkharley-devidsonquot-200087933-9776778"},
  {"title": "Крест", "link": "https://vk.com/market/product/krest-200087933-9317840"},
  {"title": "Пряжка \"Полемика\"", "link": "https://vk.com/market/product/pryazhka-quotpolemikaquot-200087933-9310515"},
  {"title": "Нейзильбер марки МНЦ в гранулах.", "link": "https://vk.com/market/product/neyzilber-marki-mnts-v-granulakh-200087933-9219792"},
  {"title": "Бронза марки БрА10Ж3 в гранулах.", "link": "https://vk.com/market/product/bronza-marki-bra10zh3-v-granulakh-200087933-9219466"},
  {"title": "Бронза марки БрО10Ф1 в гранулах.", "link": "https://vk.com/market/product/bronza-marki-bro10f1-v-granulakh-200087933-9219269"},
  {"title": "Латунь марки Л63 в гранулах.", "link": "https://vk.com/market/product/latun-marki-l63-v-granulakh-200087933-9218565"},
  {"title": "Значок Великие Луки", "link": "https://vk.com/market/product/znachok-velikie-luki-200087933-9028769"},
  {"title": "Пряжка \"Эдди\"", "link": "https://vk.com/market/product/pryazhka-quoteddiquot-200087933-7261367"},
  {"title": "Сувенир \"А. С. Пушкин\"", "link": "https://vk.com/market/product/suvenir-quota-s-pushkinquot-200087933-5059615"}
];

// Функция для получения URL большого изображения со страницы товара
async function getLargeImageUrl(productLink) {
  // Открываем страницу товара
  window.location.href = productLink;
  await new Promise(resolve => setTimeout(resolve, 3000)); // Ждем загрузки
  
  // Находим большое изображение
  const button = Array.from(document.querySelectorAll('button')).find(btn => 
    btn.textContent.includes('Открыть фотографию')
  );
  
  if (button) {
    const img = button.querySelector('img');
    if (img) return img.src;
  }
  
  // Ищем большое изображение в галерее
  const images = Array.from(document.querySelectorAll('img')).filter(img => 
    img.src && img.src.includes('userapi.com') && 
    (img.src.includes('/s/v1/') || (!img.src.includes('thumb') && !img.src.includes('size=')))
  );
  
  if (images.length > 0) {
    return images[0].src;
  }
  
  return null;
}

// Скачиваем изображение через fetch
async function downloadImage(imageUrl) {
  try {
    const response = await fetch(imageUrl, {
      headers: {
        'Referer': 'https://vk.com/'
      }
    });
    
    if (!response.ok) {
      return { success: false, error: 'HTTP ' + response.status };
    }
    
    const blob = await response.blob();
    const base64 = await new Promise((resolve) => {
      const reader = new FileReader();
      reader.onloadend = () => resolve(reader.result);
      reader.readAsDataURL(blob);
    });
    
    return { success: true, base64: base64 };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

// Основная функция
(async () => {
  const results = [];
  
  for (let i = 0; i < products.length; i++) {
    const product = products[i];
    console.log(`Processing ${i + 1}/${products.length}: ${product.title}`);
    
    // Получаем URL большого изображения
    const imageUrl = await getLargeImageUrl(product.link);
    
    if (!imageUrl) {
      results.push({
        index: i,
        title: product.title,
        success: false,
        error: 'Image URL not found'
      });
      continue;
    }
    
    // Скачиваем изображение
    const downloadResult = await downloadImage(imageUrl);
    
    if (downloadResult.success) {
      results.push({
        index: i,
        title: product.title,
        imageUrl: imageUrl,
        base64: downloadResult.base64,
        success: true
      });
    } else {
      results.push({
        index: i,
        title: product.title,
        success: false,
        error: downloadResult.error
      });
    }
    
    // Задержка между запросами
    await new Promise(resolve => setTimeout(resolve, 2000));
  }
  
  return results;
})();
