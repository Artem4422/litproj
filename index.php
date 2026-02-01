<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags - Загружаются динамически из админ-панели -->
    <?php require_once __DIR__ . '/meta_tags.php'; ?>
    
    <!-- Theme Color -->
    <meta name="theme-color" content="#5a4a3a">
    <meta name="msapplication-TileColor" content="#5a4a3a">
    
    <!-- Mobile Optimization -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ТРИОЛЬ">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    
    <!-- Additional SEO -->
    <meta name="format-detection" content="telephone=yes">
    <meta name="HandheldFriendly" content="true">
    <meta name="MobileOptimized" content="320">
    
    <!-- Search Engine Verification (add your verification codes when available) -->
    <!-- <meta name="google-site-verification" content="YOUR_GOOGLE_VERIFICATION_CODE"> -->
    <!-- <meta name="yandex-verification" content="YOUR_YANDEX_VERIFICATION_CODE"> -->
    
    <!-- Content Security -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "@id": "https://triol-foundry.ru/#organization",
      "name": "ЛИТЕЙНАЯ МАСТЕРСКАЯ ТРИОЛЬ",
      "alternateName": "ТРИОЛЬ",
      "url": "https://triol-foundry.ru/",
      "logo": "https://triol-foundry.ru/AVA.jpg",
      "image": "https://triol-foundry.ru/AVA.jpg",
      "description": "Литейная мастерская ТРИОЛЬ - художественное литье из бронзы, архитектурные элементы, памятные таблички. Традиции мастерства в современном исполнении.",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "ул. Примерная, д. 1",
        "addressLocality": "Москва",
        "addressCountry": "RU"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 55.7558,
        "longitude": 37.6173
      },
      "telephone": "+7 (999) 123-45-67",
      "email": "info@triol-foundry.ru",
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
          "opens": "09:00",
          "closes": "18:00"
        },
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": "Saturday",
          "opens": "10:00",
          "closes": "16:00"
        }
      ],
      "priceRange": "$$",
      "areaServed": {
        "@type": "City",
        "name": "Москва"
      },
      "sameAs": []
    }
    </script>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "ЛИТЕЙНАЯ МАСТЕРСКАЯ ТРИОЛЬ",
      "url": "https://triol-foundry.ru/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://triol-foundry.ru/?s={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Главная",
          "item": "https://triol-foundry.ru/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Товары",
          "item": "https://triol-foundry.ru/#products"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "Заказать услугу",
          "item": "https://triol-foundry.ru/#order"
        },
        {
          "@type": "ListItem",
          "position": 4,
          "name": "Контакты",
          "item": "https://triol-foundry.ru/#contacts"
        }
      ]
    }
    </script>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ItemList",
      "name": "Товары литейной мастерской ТРИОЛЬ",
      "description": "Каталог продукции литейной мастерской ТРИОЛЬ: художественное литье из бронзы, архитектурные элементы, памятные таблички",
      "itemListElement": [
        {
          "@type": "Product",
          "position": 1,
          "name": "Художественная скульптура",
          "description": "Уникальные художественные изделия из бронзы. Каждая скульптура изготавливается вручную опытными мастерами с использованием традиционных техник литья.",
          "category": "Художественное литье",
          "brand": {
            "@type": "Brand",
            "name": "ТРИОЛЬ"
          },
          "offers": {
            "@type": "Offer",
            "price": "15000",
            "priceCurrency": "RUB",
            "availability": "https://schema.org/InStock",
            "url": "https://triol-foundry.ru/#products"
          },
          "material": "Бронза"
        },
        {
          "@type": "Product",
          "position": 2,
          "name": "Архитектурные элементы",
          "description": "Декоративные элементы для фасадов и интерьеров. Изготавливаются из высококачественных металлов с антикоррозийным покрытием.",
          "category": "Архитектурное литье",
          "brand": {
            "@type": "Brand",
            "name": "ТРИОЛЬ"
          },
          "offers": {
            "@type": "Offer",
            "price": "8000",
            "priceCurrency": "RUB",
            "availability": "https://schema.org/InStock",
            "url": "https://triol-foundry.ru/#products"
          },
          "material": "Металл"
        },
        {
          "@type": "Product",
          "position": 3,
          "name": "Памятные таблички",
          "description": "Изготовление памятных и информационных табличек из бронзы, латуни и других металлов. Возможность нанесения гравировки, эмалирования и других видов обработки.",
          "category": "Памятные таблички",
          "brand": {
            "@type": "Brand",
            "name": "ТРИОЛЬ"
          },
          "offers": {
            "@type": "Offer",
            "price": "3000",
            "priceCurrency": "RUB",
            "availability": "https://schema.org/InStock",
            "url": "https://triol-foundry.ru/#products"
          },
          "material": "Бронза, Латунь"
        },
        {
          "@type": "Product",
          "position": 4,
          "name": "Каминные решетки",
          "description": "Декоративные решетки для каминов из металла. Изготавливаются по индивидуальным размерам с учетом стиля интерьера.",
          "category": "Каминные аксессуары",
          "brand": {
            "@type": "Brand",
            "name": "ТРИОЛЬ"
          },
          "offers": {
            "@type": "Offer",
            "price": "12000",
            "priceCurrency": "RUB",
            "availability": "https://schema.org/InStock",
            "url": "https://triol-foundry.ru/#products"
          },
          "material": "Металл"
        },
        {
          "@type": "Product",
          "position": 5,
          "name": "Сувенирная продукция",
          "description": "Корпоративные сувениры и подарки из металла. Широкий ассортимент изделий для бизнес-подарков, наград и памятных сувениров.",
          "category": "Сувениры",
          "brand": {
            "@type": "Brand",
            "name": "ТРИОЛЬ"
          },
          "offers": {
            "@type": "Offer",
            "price": "2000",
            "priceCurrency": "RUB",
            "availability": "https://schema.org/InStock",
            "url": "https://triol-foundry.ru/#products"
          },
          "material": "Металл"
        },
        {
          "@type": "Product",
          "position": 6,
          "name": "Интерьерные аксессуары",
          "description": "Уникальные аксессуары для интерьера из металла. Подсвечники, вазы, статуэтки и другие декоративные элементы.",
          "category": "Интерьерные аксессуары",
          "brand": {
            "@type": "Brand",
            "name": "ТРИОЛЬ"
          },
          "offers": {
            "@type": "Offer",
            "price": "5000",
            "priceCurrency": "RUB",
            "availability": "https://schema.org/InStock",
            "url": "https://triol-foundry.ru/#products"
          },
          "material": "Металл"
        }
      ]
    }
    </script>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Service",
      "serviceType": "Художественное литье из бронзы",
      "provider": {
        "@type": "LocalBusiness",
        "name": "ЛИТЕЙНАЯ МАСТЕРСКАЯ ТРИОЛЬ"
      },
      "areaServed": {
        "@type": "City",
        "name": "Москва"
      },
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Услуги литейной мастерской",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Художественное литье"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Промышленное литье"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Архитектурное литье"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Ремонт и реставрация"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Ювелирное литье"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Дизайн и консультации"
            }
          }
        ]
      }
    }
    </script>
</head>
<body>
    <!-- Header -->
    <header class="header" role="banner">
        <nav class="nav" role="navigation" aria-label="Основная навигация">
            <div class="logo">
                <img src="AVA.jpg" alt="Логотип литейной мастерской ТРИОЛЬ - художественное литье из бронзы" class="logo-img" width="50" height="50">
                <span class="logo-text">ТРИОЛЬ</span>
            </div>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link">Главная</a></li>
                <li><a href="#products" class="nav-link">Товары</a></li>
                <li><a href="#order" class="nav-link">Заказать услугу</a></li>
                <li><a href="#contacts" class="nav-link">Контакты</a></li>
            </ul>
            <div class="header-actions">
                <button class="cart-btn" id="cartBtn" aria-label="Открыть корзину" aria-expanded="false">
                    <svg class="cart-icon" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 18C5.9 18 5.01 18.9 5.01 20C5.01 21.1 5.9 22 7 22C8.1 22 9 21.1 9 20C9 18.9 8.1 18 7 18ZM1 2V4H3L6.6 11.59L5.25 14.04C5.09 14.32 5 14.65 5 15C5 16.1 5.9 17 7 17H19V15H7.42C7.28 15 7.17 14.89 7.17 14.75L7.2 14.63L8.1 13H15.55C16.3 13 16.96 12.59 17.3 11.97L20.88 5.5C20.96 5.34 21 5.17 21 5C21 4.45 20.55 4 20 4H5.21L4.27 2H1ZM17 18C15.9 18 15.01 18.9 15.01 20C15.01 21.1 15.9 22 17 22C18.1 22 19 21.1 19 20C19 18.9 18.1 18 17 18Z" fill="currentColor"/>
                    </svg>
                    <span class="cart-count" id="cartCount" aria-live="polite">0</span>
                </button>
            </div>
            <button class="burger-menu" aria-label="Открыть меню" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main role="main">
    <!-- Hero Section -->
    <section id="home" class="hero" itemscope itemtype="https://schema.org/Organization">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">ЛИТЕЙНАЯ МАСТЕРСКАЯ ТРИОЛЬ</h1>
            <p class="hero-subtitle">Традиции мастерства в современном исполнении</p>
            <a href="#contacts" class="btn btn-primary">Связаться с нами</a>
        </div>
        <div class="scroll-indicator">
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products" itemscope itemtype="https://schema.org/ItemList">
        <div class="container">
            <h2 class="section-title">Наши товары</h2>
            <div class="products-grid" id="productsGrid">
                <!-- Товары будут загружены динамически через JavaScript -->
            </div>
        </div>
    </section>

    <!-- Order Service Section -->
    <section id="order" class="order-service">
        <div class="container">
            <h2 class="section-title">Заказать услугу</h2>
            <div class="order-content">
                <div class="order-info">
                    <h3>Оформите заказ на услугу</h3>
                    <p>Заполните форму ниже, и наш менеджер свяжется с вами в ближайшее время для уточнения деталей заказа.</p>
                    <div class="order-features">
                        <div class="order-feature">
                            <div class="order-icon">✓</div>
                            <p>Бесплатная консультация</p>
                        </div>
                        <div class="order-feature">
                            <div class="order-icon">✓</div>
                            <p>Индивидуальный подход</p>
                        </div>
                        <div class="order-feature">
                            <div class="order-icon">✓</div>
                            <p>Гарантия качества</p>
                        </div>
                    </div>
                </div>
                <form class="order-form" id="orderForm">
                    <h3>Форма заказа</h3>
                    <div class="form-group">
                        <label>Тип услуги</label>
                        <select name="service_type" id="serviceTypeSelect" required>
                            <option value="">Загрузка услуг...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order-name">Ваше имя</label>
                        <input type="text" id="order-name" name="name" placeholder="Иван Иванов" required aria-required="true">
                    </div>
                    <div class="form-group">
                        <label for="order-phone">Телефон</label>
                        <input type="tel" id="order-phone" name="phone" placeholder="+7 (999) 123-45-67" required aria-required="true" pattern="[\+]?[0-9\s\-\(\)]+">
                    </div>
                    <div class="form-group">
                        <label for="order-email">Email</label>
                        <input type="email" id="order-email" name="email" placeholder="example@mail.ru" required aria-required="true">
                    </div>
                    <div class="form-group">
                        <label>Описание заказа</label>
                        <textarea name="description" placeholder="Опишите, что вы хотите заказать, размеры, материалы и другие детали" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Сроки выполнения (желаемые)</label>
                        <input type="text" name="deadline" placeholder="Например: неделя">
                    </div>
                    <button type="submit" class="btn btn-primary btn-order">Отправить заказ</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Contacts Section -->
    <section id="contacts" class="contacts">
        <div class="container">
            <h2 class="section-title">Контакты</h2>
            <div class="contacts-content">
                <div class="contact-info">
                    <div class="contact-item">
                        <svg class="contact-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z" fill="currentColor"/>
                        </svg>
                        <div>
                            <h3>Адрес</h3>
                            <p>г. Москва, ул. Примерная, д. 1</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <svg class="contact-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.62 10.79C8.06 13.62 10.38 15.94 13.21 17.38L15.41 15.18C15.69 14.9 16.08 14.82 16.43 14.93C17.55 15.3 18.75 15.5 20 15.5C20.55 15.5 21 15.95 21 16.5V20C21 20.55 20.55 21 20 21C10.61 21 3 13.39 3 4C3 3.45 3.45 3 4 3H7.5C8.05 3 8.5 3.45 8.5 4C8.5 5.25 8.7 6.45 9.07 7.57C9.18 7.92 9.1 8.31 8.82 8.59L6.62 10.79Z" fill="currentColor"/>
                        </svg>
                        <div>
                            <h3>Телефон</h3>
                            <p>+7 (999) 123-45-67</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <svg class="contact-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="currentColor"/>
                        </svg>
                        <div>
                            <h3>Email</h3>
                            <p>info@triol-foundry.ru</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <svg class="contact-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12C2 17.52 6.47 22 11.99 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 11.99 2ZM12 20C7.58 20 4 16.42 4 12C4 7.58 7.58 4 12 4C16.42 4 20 7.58 20 12C20 16.42 16.42 20 12 20ZM12.5 7H11V13L16.25 16.15L17 14.92L12.5 12.25V7Z" fill="currentColor"/>
                        </svg>
                        <div>
                            <h3>Режим работы</h3>
                            <p>Пн-Пт: 9:00 - 18:00<br>Сб: 10:00 - 16:00</p>
                        </div>
                    </div>
                </div>
                <form class="contact-form" id="contactForm">
                    <h3>Напишите нам</h3>
                    <input type="text" name="name" placeholder="Ваше имя" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="tel" name="phone" placeholder="Телефон">
                    <textarea name="message" placeholder="Сообщение" rows="5" required></textarea>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    </section>

    </main>
    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="AVA.jpg" alt="Логотип литейной мастерской ТРИОЛЬ" class="footer-logo-img" width="40" height="40">
                    <span>ЛИТЕЙНАЯ МАСТЕРСКАЯ ТРИОЛЬ</span>
                </div>
                <p class="footer-text">&copy; 2026 ТРИОЛЬ. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <!-- Product Modal -->
    <div class="modal" id="productModal" role="dialog" aria-labelledby="modalProductName" aria-modal="true">
        <div class="modal-content modal-product">
            <span class="modal-close" id="closeProductModal" aria-label="Закрыть">&times;</span>
            <div class="modal-product-content">
                <div class="modal-product-image">
                    <img id="modalProductImage" src="" alt="" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                    <div class="product-placeholder-large" id="modalProductImagePlaceholder">🖼️</div>
                </div>
                <div class="modal-product-info">
                    <h2 class="modal-product-name" id="modalProductName"></h2>
                    <div class="modal-product-price" id="modalProductPrice"></div>
                    <p class="modal-product-description" id="modalProductDescription"></p>
                    <div class="modal-product-actions">
                        <button class="btn btn-primary btn-add-cart-modal" id="addToCartFromModal">Добавить в корзину</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar" role="dialog" aria-labelledby="cartSidebarTitle" aria-modal="true">
        <div class="cart-sidebar-overlay" id="cartOverlay" aria-label="Закрыть корзину"></div>
        <div class="cart-sidebar-content">
            <div class="cart-sidebar-header">
                <h2 class="cart-sidebar-title" id="cartSidebarTitle">
                    <svg class="cart-icon-header" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 18C5.9 18 5.01 18.9 5.01 20C5.01 21.1 5.9 22 7 22C8.1 22 9 21.1 9 20C9 18.9 8.1 18 7 18ZM1 2V4H3L6.6 11.59L5.25 14.04C5.09 14.32 5 14.65 5 15C5 16.1 5.9 17 7 17H19V15H7.42C7.28 15 7.17 14.89 7.17 14.75L7.2 14.63L8.1 13H15.55C16.3 13 16.96 12.59 17.3 11.97L20.88 5.5C20.96 5.34 21 5.17 21 5C21 4.45 20.55 4 20 4H5.21L4.27 2H1ZM17 18C15.9 18 15.01 18.9 15.01 20C15.01 21.1 15.9 22 17 22C18.1 22 19 21.1 19 20C19 18.9 18.1 18 17 18Z" fill="currentColor"/>
                    </svg>
                    Корзина
                    <span class="cart-items-count" id="cartItemsCount" aria-label="Количество товаров">(0)</span>
                </h2>
                <button class="cart-sidebar-close" id="closeCartSidebar" aria-label="Закрыть корзину">&times;</button>
            </div>
            
            <div class="cart-sidebar-body">
                <div class="cart-items-container" id="cartItemsContainer">
                    <div class="cart-empty-state" id="cartEmptyState">
                        <svg class="cart-empty-icon" width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 18C5.9 18 5.01 18.9 5.01 20C5.01 21.1 5.9 22 7 22C8.1 22 9 21.1 9 20C9 18.9 8.1 18 7 18ZM1 2V4H3L6.6 11.59L5.25 14.04C5.09 14.32 5 14.65 5 15C5 16.1 5.9 17 7 17H19V15H7.42C7.28 15 7.17 14.89 7.17 14.75L7.2 14.63L8.1 13H15.55C16.3 13 16.96 12.59 17.3 11.97L20.88 5.5C20.96 5.34 21 5.17 21 5C21 4.45 20.55 4 20 4H5.21L4.27 2H1ZM17 18C15.9 18 15.01 18.9 15.01 20C15.01 21.1 15.9 22 17 22C18.1 22 19 21.1 19 20C19 18.9 18.1 18 17 18Z" fill="currentColor" opacity="0.3"/>
                        </svg>
                        <p class="cart-empty-text">Ваша корзина пуста</p>
                        <p class="cart-empty-hint">Добавьте товары из каталога</p>
                        <button class="btn btn-secondary btn-continue-shopping" id="continueShoppingBtn">Продолжить покупки</button>
                    </div>
                    <div class="cart-items-list" id="cartItemsList"></div>
                </div>
            </div>
            
            <div class="cart-sidebar-footer" id="cartSidebarFooter" style="display: none;">
                <div class="cart-summary-section">
                    <div class="cart-summary-row">
                        <span class="cart-summary-label">Товары, <span id="cartItemsSummaryCount">0</span>:</span>
                        <span class="cart-summary-value" id="cartSubtotalPrice">0 ₽</span>
                    </div>
                    <div class="cart-summary-divider"></div>
                    <div class="cart-summary-row cart-summary-total">
                        <span class="cart-summary-label">Итого:</span>
                        <span class="cart-summary-value cart-total-price" id="cartTotalPrice">0 ₽</span>
                    </div>
                </div>
                
                <button class="btn btn-primary btn-checkout-full" id="checkoutBtn">
                    Перейти к оформлению
                </button>
                <p class="cart-delivery-hint">Сроки и условия доставки определяются при оформлении заказа</p>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal" id="checkoutModal" role="dialog" aria-labelledby="checkoutModalTitle" aria-modal="true">
        <div class="modal-content modal-checkout">
            <span class="modal-close" id="closeCheckoutModal" aria-label="Закрыть">&times;</span>
            <h2 class="modal-title" id="checkoutModalTitle">Оформление заказа</h2>
            <form class="checkout-form" id="checkoutForm">
                <div class="checkout-section">
                    <h3>Данные получателя</h3>
                    <div class="form-group">
                        <label>ФИО *</label>
                        <input type="text" name="fullName" placeholder="Иван Иванов" required>
                    </div>
                    <div class="form-group">
                        <label>Телефон *</label>
                        <input type="tel" name="phone" placeholder="+7 (999) 123-45-67" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" placeholder="example@mail.ru" required>
                    </div>
                </div>
                
                <div class="checkout-section">
                    <h3>Адрес доставки</h3>
                    <div class="form-group">
                        <label>Город *</label>
                        <input type="text" name="city" id="deliveryCity" placeholder="Определяется автоматически..." required>
                        <small class="form-hint">Город определяется по вашему IP</small>
                    </div>
                    <div class="form-group">
                        <label>Улица *</label>
                        <input type="text" name="street" placeholder="Центральная улица" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Дом *</label>
                            <input type="text" name="house" placeholder="1" required>
                        </div>
                        <div class="form-group">
                            <label>Квартира/Офис</label>
                            <input type="text" name="apartment" placeholder="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Комментарий к доставке</label>
                        <textarea name="deliveryComment" rows="3" placeholder="Дополнительная информация для курьера"></textarea>
                    </div>
                </div>

                <div class="checkout-section">
                    <h3>Способ доставки</h3>
                    <div class="delivery-options" id="deliveryOptions">
                        <div class="delivery-option">
                            <input type="radio" name="deliveryType" value="pickup" id="deliveryPickup" checked>
                            <label for="deliveryPickup">
                                <span class="delivery-name">Самовывоз (Москва)</span>
                                <span class="delivery-price">Бесплатно</span>
                                <span class="delivery-time">В день заказа</span>
                            </label>
                        </div>
                        <div class="delivery-option">
                            <input type="radio" name="deliveryType" value="standard" id="deliveryStandard">
                            <label for="deliveryStandard">
                                <span class="delivery-name">Стандартная доставка</span>
                                <span class="delivery-price" id="standardPrice">Рассчитывается...</span>
                                <span class="delivery-time">3-5 дней</span>
                            </label>
                        </div>
                        <div class="delivery-option">
                            <input type="radio" name="deliveryType" value="express" id="deliveryExpress">
                            <label for="deliveryExpress">
                                <span class="delivery-name">Экспресс доставка</span>
                                <span class="delivery-price" id="expressPrice">Рассчитывается...</span>
                                <span class="delivery-time">1-2 дня</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="checkout-summary">
                    <div class="checkout-summary-item">
                        <span>Товары:</span>
                        <span id="checkoutSubtotal">0 ₽</span>
                    </div>
                    <div class="checkout-summary-item">
                        <span>Доставка:</span>
                        <span id="checkoutDelivery">0 ₽</span>
                    </div>
                    <div class="checkout-summary-total">
                        <span>К оплате:</span>
                        <span id="checkoutTotal">0 ₽</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-submit-order">Подтвердить заказ</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
