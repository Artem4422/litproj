// Mobile Menu Toggle
const burgerMenu = document.querySelector('.burger-menu');
const navMenu = document.querySelector('.nav-menu');

if (burgerMenu && navMenu) {
    burgerMenu.addEventListener('click', () => {
        const isActive = navMenu.classList.toggle('active');
        burgerMenu.classList.toggle('active');
        burgerMenu.setAttribute('aria-expanded', isActive);
    });
}

// Close menu when clicking on a link
const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        burgerMenu.classList.remove('active');
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const headerOffset = 80;
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Header scroll effect
let lastScroll = 0;
const header = document.querySelector('.header');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        header.style.background = 'rgba(0, 0, 0, 0.98)';
        header.style.boxShadow = '0 2px 20px rgba(218, 165, 32, 0.2)';
    } else {
        header.style.background = 'rgba(0, 0, 0, 0.95)';
        header.style.boxShadow = '0 2px 10px rgba(218, 165, 32, 0.1)';
    }
    
    lastScroll = currentScroll;
});

// Intersection Observer for fade-in animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for animation
document.addEventListener('DOMContentLoaded', () => {
    const animateElements = document.querySelectorAll('.feature-card, .service-card, .gallery-item, .contact-item, .product-card');
    
    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    // Product card buttons are handled in cart initialization section
});

// Form submission handler
function showSuccessMessage(message, type = 'success') {
    const messageEl = document.createElement('div');
    const bgColor = type === 'success' 
        ? 'linear-gradient(135deg, #8B4513 0%, #DAA520 100%)'
        : 'linear-gradient(135deg, #8B0000 0%, #DC143C 100%)';
    
    messageEl.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${bgColor};
        color: #fff;
        padding: 1rem 2rem;
        border-radius: 5px;
        box-shadow: 0 4px 15px rgba(218, 165, 32, 0.3);
        z-index: 10000;
        animation: slideIn 0.3s ease;
        font-weight: bold;
        max-width: 400px;
        word-wrap: break-word;
    `;
    messageEl.setAttribute('role', 'alert');
    messageEl.setAttribute('aria-live', 'assertive');
    messageEl.textContent = message;
    document.body.appendChild(messageEl);
    
    // Remove message after 3 seconds
    setTimeout(() => {
        messageEl.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            messageEl.remove();
        }, 300);
    }, 3000);
}

function showErrorMessage(message) {
    showSuccessMessage(message, 'error');
}

// Form validation
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    const errors = [];
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('error');
            errors.push(`Поле "${field.previousElementSibling?.textContent || field.name}" обязательно для заполнения`);
        } else {
            field.classList.remove('error');
            
            // Email validation
            if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) {
                isValid = false;
                field.classList.add('error');
                errors.push('Введите корректный email адрес');
            }
            
            // Phone validation
            if (field.type === 'tel' && !/^[\d\s\-\+\(\)]+$/.test(field.value)) {
                isValid = false;
                field.classList.add('error');
                errors.push('Введите корректный номер телефона');
            }
        }
    });
    
    if (!isValid && errors.length > 0) {
        showErrorMessage(errors[0]);
    }
    
    return isValid;
}

// Contact form submission with validation
const contactForm = document.querySelector('.contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(contactForm);
        const name = formData.get('name') || '';
        const email = formData.get('email') || '';
        const message = formData.get('message') || '';
        
        // Get input elements for focus
        const nameInput = contactForm.querySelector('input[name="name"]');
        const emailInput = contactForm.querySelector('input[name="email"]');
        const messageTextarea = contactForm.querySelector('textarea[name="message"]');
        
        let isValid = true;
        let errorMessage = '';
        
        // Validate name
        if (!name.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите ваше имя';
            if (nameInput) nameInput.focus();
        }
        // Validate email
        else if (!email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите корректный email';
            if (emailInput) emailInput.focus();
        }
        // Validate message
        else if (!message.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, напишите сообщение';
            if (messageTextarea) messageTextarea.focus();
        }
        
        if (!isValid) {
            showErrorMessage(errorMessage);
            return;
        }
        
        // Show loading state
        const submitBtn = contactForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Отправка...';
        
        try {
            // Prepare data for submission
            const data = {
                name: name,
                email: email,
                phone: formData.get('phone') || '',
                message: message
            };
            
            // Here you would normally send the data to a server
            console.log('Contact form submitted:', data);
            
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            showSuccessMessage('Спасибо! Ваше сообщение отправлено.');
            
            // Reset form
            contactForm.reset();
        } catch (error) {
            console.error('Error submitting contact form:', error);
            showErrorMessage('Произошла ошибка при отправке сообщения. Попробуйте позже.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// Load services for order form
async function loadServices() {
    const serviceSelect = document.getElementById('serviceTypeSelect');
    if (!serviceSelect) return;
    
    try {
        const response = await fetch('/api/services.php');
        if (!response.ok) {
            throw new Error('Failed to load services');
        }
        const services = await response.json();
        
        serviceSelect.innerHTML = '<option value="">Выберите услугу</option>';
        
        if (services.length === 0) {
            serviceSelect.innerHTML = '<option value="">Услуги не найдены</option>';
            return;
        }
        
        services.forEach(service => {
            const option = document.createElement('option');
            option.value = service.id;
            option.textContent = service.name;
            serviceSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading services:', error);
        serviceSelect.innerHTML = '<option value="">Ошибка загрузки услуг</option>';
    }
}

// Order form submission with validation
const orderForm = document.querySelector('.order-form');
if (orderForm) {
    // Load services when form is ready
    loadServices();
    
    orderForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Validate form
        const serviceType = orderForm.querySelector('select[name="service_type"]');
        const nameInput = orderForm.querySelector('input[name="name"]');
        const phoneInput = orderForm.querySelector('input[name="phone"]');
        const emailInput = orderForm.querySelector('input[name="email"]');
        const descriptionTextarea = orderForm.querySelector('textarea[name="description"]');
        
        let isValid = true;
        let errorMessage = '';
        
        // Validate service type
        if (!serviceType || !serviceType.value) {
            isValid = false;
            errorMessage = 'Пожалуйста, выберите тип услуги';
            if (serviceType) serviceType.focus();
        }
        // Validate name
        else if (!nameInput || !nameInput.value.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите ваше имя';
            if (nameInput) nameInput.focus();
        }
        // Validate phone
        else if (!phoneInput || !phoneInput.value.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите телефон';
            if (phoneInput) phoneInput.focus();
        }
        // Validate email
        else if (!emailInput || !emailInput.value.trim() || !emailInput.validity.valid) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите корректный email';
            if (emailInput) emailInput.focus();
        }
        // Validate description
        else if (!descriptionTextarea || !descriptionTextarea.value.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, опишите заказ';
            if (descriptionTextarea) descriptionTextarea.focus();
        }
        
        if (!isValid) {
            showErrorMessage(errorMessage);
            return;
        }
        
        // Show loading state
        const submitBtn = orderForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Отправка...';
        
        try {
            // Get form data
            const formData = new FormData(orderForm);
            const data = {
                service_type: parseInt(formData.get('service_type')),
                name: formData.get('name'),
                phone: formData.get('phone'),
                email: formData.get('email'),
                description: formData.get('description'),
                deadline: formData.get('deadline') || ''
            };
            
            // Send to server
            console.log('Sending order data:', data);
            
            const response = await fetch('/api/submit_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server error:', errorText);
                throw new Error('Ошибка сервера: ' + response.status);
            }
            
            const result = await response.json();
            console.log('Response data:', result);
            
            if (result.success) {
                showSuccessMessage('Спасибо! Ваш заказ принят. Мы свяжемся с вами в ближайшее время.');
                // Reset form
                orderForm.reset();
                // Reload services
                loadServices();
            } else {
                const errorMsg = result.errors ? result.errors.join(', ') : (result.error || 'Произошла ошибка при отправке заказа');
                showErrorMessage(errorMsg);
            }
        } catch (error) {
            console.error('Error submitting order form:', error);
            showErrorMessage('Произошла ошибка при отправке заказа. Попробуйте позже.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// Add CSS animations for success message
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Parallax effect for hero section
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.hero');
    if (hero && scrolled < window.innerHeight) {
        hero.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});

// Gallery item click handler (for future lightbox)
const galleryItems = document.querySelectorAll('.gallery-item');
galleryItems.forEach(item => {
    item.addEventListener('click', () => {
        // Placeholder for lightbox functionality
        console.log('Gallery item clicked');
    });
});

// Product card hover effect
document.addEventListener('DOMContentLoaded', () => {
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// Add active state to navigation links based on scroll position
const sections = document.querySelectorAll('section[id]');

window.addEventListener('scroll', () => {
    const scrollY = window.pageYOffset;
    
    sections.forEach(section => {
        const sectionHeight = section.offsetHeight;
        const sectionTop = section.offsetTop - 100;
        const sectionId = section.getAttribute('id');
        const navLink = document.querySelector(`.nav-link[href="#${sectionId}"]`);
        
        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            navLinks.forEach(link => link.classList.remove('active'));
            if (navLink) {
                navLink.classList.add('active');
            }
        }
    });
});

// Add active class styles
const navStyle = document.createElement('style');
navStyle.textContent = `
    .nav-link.active {
        color: var(--gold) !important;
        opacity: 0.9;
    }
    .nav-link.active::after {
        width: 100% !important;
    }
`;
document.head.appendChild(navStyle);

// Cart functionality
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let selectedDeliveryType = 'pickup';

// Update cart count
function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    const cartItemsCount = document.getElementById('cartItemsCount');
    
    const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
    
    if (cartCount) {
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
    }
    
    if (cartItemsCount) {
        cartItemsCount.textContent = `(${totalItems})`;
    }
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    renderCart();
}

// Add product to cart
function addToCart(productId, productName, productPrice, productDescription) {
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        // Increase quantity if item already exists
        existingItem.quantity = (existingItem.quantity || 1) + 1;
        saveCart();
        showSuccessMessage('Количество товара увеличено!');
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: parseInt(productPrice),
            description: productDescription,
            quantity: 1
        });
        saveCart();
        showSuccessMessage('Товар добавлен в корзину!');
    }
    
    // Open cart sidebar asynchronously to prevent hanging
    setTimeout(() => {
        const sidebar = document.getElementById('cartSidebar');
        if (sidebar && !sidebar.classList.contains('active')) {
            openCartSidebar();
        }
    }, 100);
}

// Remove product from cart with confirmation
function removeFromCart(productId) {
    const item = cart.find(item => item.id === productId);
    if (!item) return;
    
    // Show confirmation dialog
    if (confirm(`Удалить "${item.name}" из корзины?`)) {
        cart = cart.filter(item => String(item.id) !== String(productId));
        saveCart();
        showSuccessMessage('Товар удален из корзины');
    }
}

// Update product quantity in cart
function updateCartQuantity(productId, newQuantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        if (newQuantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = Math.max(1, Math.min(99, parseInt(newQuantity)));
            saveCart();
        }
    }
}

// Render cart items
function renderCart() {
    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const cartEmptyState = document.getElementById('cartEmptyState');
    const cartItemsList = document.getElementById('cartItemsList');
    const cartSidebarFooter = document.getElementById('cartSidebarFooter');
    
    if (!cartItemsContainer) return;
    
    if (cart.length === 0) {
        if (cartEmptyState) cartEmptyState.style.display = 'flex';
        if (cartItemsList) cartItemsList.innerHTML = '';
        if (cartSidebarFooter) cartSidebarFooter.style.display = 'none';
        return;
    }
    
    if (cartEmptyState) cartEmptyState.style.display = 'none';
    if (cartSidebarFooter) cartSidebarFooter.style.display = 'flex';
    
    if (cartItemsList) {
        cartItemsList.innerHTML = cart.map(item => {
            const quantity = item.quantity || 1;
            const itemTotal = parseInt(item.price) * quantity;
            
            return `
                <div class="cart-item" data-product-id="${item.id}">
                    <div class="cart-item-image">🖼️</div>
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-controls">
                            <div class="cart-item-quantity">
                                <button class="cart-quantity-btn" onclick="updateCartQuantity('${item.id}', ${quantity - 1})">–</button>
                                <input type="number" class="cart-quantity-input" value="${quantity}" min="1" max="99" 
                                       onchange="updateCartQuantity('${item.id}', this.value)" 
                                       onblur="if(this.value < 1) this.value = 1; updateCartQuantity('${item.id}', this.value)">
                                <button class="cart-quantity-btn" onclick="updateCartQuantity('${item.id}', ${quantity + 1})">+</button>
                            </div>
                            <div class="cart-item-price-total">${parseInt(item.price).toLocaleString('ru-RU')} ₽</div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.25rem;">
                            <span class="cart-item-price">от ${parseInt(item.price).toLocaleString('ru-RU')} ₽</span>
                            <button class="cart-item-remove" onclick="removeFromCart('${item.id}')">Удалить</button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    // Update cart totals
    updateCartTotals();
    
    // Update items count in summary
    const cartItemsSummaryCount = document.getElementById('cartItemsSummaryCount');
    if (cartItemsSummaryCount) {
        const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        cartItemsSummaryCount.textContent = totalItems;
    }
}

// Update cart totals
function updateCartTotals() {
    const cartSubtotalPrice = document.getElementById('cartSubtotalPrice');
    const cartTotalPrice = document.getElementById('cartTotalPrice');
    
    if (!cartSubtotalPrice || !cartTotalPrice) return;
    
    // Calculate subtotal
    let subtotal = 0;
    cart.forEach(item => {
        const quantity = item.quantity || 1;
        subtotal += parseInt(item.price) * quantity;
    });
    
    cartSubtotalPrice.textContent = `${subtotal.toLocaleString('ru-RU')} ₽`;
    
    // Total is same as subtotal (delivery will be calculated at checkout)
    cartTotalPrice.textContent = `${subtotal.toLocaleString('ru-RU')} ₽`;
    
    // Update items count in summary
    const cartItemsSummaryCount = document.getElementById('cartItemsSummaryCount');
    if (cartItemsSummaryCount) {
        const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        cartItemsSummaryCount.textContent = totalItems;
    }
}

// Product Modal - Initialize after DOM is ready
let productModal, closeProductModal;
let currentProductId = null;

function openProductModal(productCard) {
    if (!productModal) {
        productModal = document.getElementById('productModal');
    }
    if (!productModal) return;
    
    const productId = productCard.dataset.productId;
    const productName = productCard.dataset.productName;
    const productPrice = productCard.dataset.productPrice;
    const productDescription = productCard.dataset.productDescription;
    const productImage = productCard.dataset.productImage || '';
    
    currentProductId = productId;
    
    const modalName = document.getElementById('modalProductName');
    const modalPrice = document.getElementById('modalProductPrice');
    const modalDescription = document.getElementById('modalProductDescription');
    const modalImage = document.getElementById('modalProductImage');
    const modalImagePlaceholder = document.getElementById('modalProductImagePlaceholder');
    
    if (modalName) modalName.textContent = productName;
    if (modalPrice) modalPrice.textContent = `от ${parseInt(productPrice).toLocaleString('ru-RU')} ₽`;
    if (modalDescription) modalDescription.textContent = productDescription;
    
    if (modalImage && modalImagePlaceholder) {
        if (productImage && productImage.trim()) {
            modalImage.src = productImage;
            modalImage.alt = productName;
            modalImage.style.display = 'block';
            modalImagePlaceholder.style.display = 'none';
        } else {
            modalImage.style.display = 'none';
            modalImagePlaceholder.style.display = 'flex';
        }
    }
    
    productModal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeProductModalFunc() {
    if (!productModal) {
        productModal = document.getElementById('productModal');
    }
    if (productModal) {
        productModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Cart Sidebar - Initialize after DOM is ready
let cartSidebar, cartBtn, closeCartSidebarBtn, cartOverlay, continueShoppingBtn;

function openCartSidebar() {
    if (!cartSidebar) {
        cartSidebar = document.getElementById('cartSidebar');
    }
    if (cartSidebar) {
        renderCart();
        cartSidebar.classList.add('active');
        document.body.style.overflow = 'hidden';
        // Update aria attributes
        const cartBtn = document.getElementById('cartBtn');
        if (cartBtn) {
            cartBtn.setAttribute('aria-expanded', 'true');
        }
    }
}

function closeCartSidebar() {
    if (!cartSidebar) {
        cartSidebar = document.getElementById('cartSidebar');
    }
    if (cartSidebar) {
        cartSidebar.classList.remove('active');
        document.body.style.overflow = 'auto';
        // Update aria attributes
        const cartBtn = document.getElementById('cartBtn');
        if (cartBtn) {
            cartBtn.setAttribute('aria-expanded', 'false');
        }
    }
}

// Make functions globally available
window.removeFromCart = removeFromCart;
window.updateCartQuantity = updateCartQuantity;
window.openCartSidebar = openCartSidebar;
window.closeCartSidebar = closeCartSidebar;

// Load products from API
async function loadProducts() {
    const productsGrid = document.getElementById('productsGrid');
    if (!productsGrid) return;
    
    try {
        const response = await fetch('/api/products.php');
        if (!response.ok) {
            throw new Error('Failed to load products');
        }
        const products = await response.json();
        
        // Clear existing products
        productsGrid.innerHTML = '';
        
        if (products.length === 0) {
            productsGrid.innerHTML = '<p style="text-align: center; padding: 2rem; color: #DAA520;">Товары не найдены</p>';
            return;
        }
        
        // Render products
        products.forEach(product => {
            const priceNum = parseInt(product.price.replace(/[^\d]/g, '')) || 0;
            const priceDisplay = product.price || '0 ₽';
            
            const productCard = document.createElement('article');
            productCard.className = 'product-card';
            productCard.setAttribute('data-product-id', product.id);
            productCard.setAttribute('data-product-name', product.name);
            productCard.setAttribute('data-product-price', priceNum);
            productCard.setAttribute('data-product-description', product.description || '');
            productCard.setAttribute('data-product-image', product.image || '');
            productCard.setAttribute('itemscope', '');
            productCard.setAttribute('itemtype', 'https://schema.org/Product');
            
            const imageHtml = product.image && product.image.trim() 
                ? `<img src="${product.image}" alt="${product.name}" class="product-img">`
                : '<div class="product-placeholder" aria-label="Изображение ' + product.name + '">🖼️</div>';
            
            productCard.innerHTML = `
                <div class="product-image">
                    ${imageHtml}
                </div>
                <div class="product-info">
                    <h3 class="product-name" itemprop="name">${escapeHtml(product.name)}</h3>
                    <p class="product-description" itemprop="description">${escapeHtml(product.description || '')}</p>
                    <div class="product-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                        <meta itemprop="price" content="${priceNum}">
                        <meta itemprop="priceCurrency" content="RUB">
                        <meta itemprop="availability" content="https://schema.org/InStock">
                        ${priceDisplay}
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-product btn-view">Подробнее</button>
                        <button class="btn btn-product btn-add-cart">В корзину</button>
                    </div>
                </div>
            `;
            
            productsGrid.appendChild(productCard);
        });
        
        // Attach event handlers to new product cards
        attachProductCardHandlers();
        
    } catch (error) {
        console.error('Error loading products:', error);
        const productsGrid = document.getElementById('productsGrid');
        if (productsGrid) {
            productsGrid.innerHTML = '<p style="text-align: center; padding: 2rem; color: #DAA520;">Ошибка загрузки товаров</p>';
        }
    }
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Attach event handlers to product cards
function attachProductCardHandlers() {
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        // View button
        const viewBtn = card.querySelector('.btn-view');
        if (viewBtn) {
            viewBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                openProductModal(card);
            });
        }
        
        // Add to cart button
        const addCartBtn = card.querySelector('.btn-add-cart');
        if (addCartBtn) {
            addCartBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const productId = card.dataset.productId;
                const productName = card.dataset.productName;
                const productPrice = card.dataset.productPrice;
                const productDescription = card.dataset.productDescription;
                addToCart(productId, productName, productPrice, productDescription);
            });
        }
    });
}

// Initialize cart functionality
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    renderCart();
    
    // Load products from API
    loadProducts();
    
    // Initialize delivery calculation
    setTimeout(() => {
        if (userCity === 'Москва') {
            getUserLocation();
        } else {
            calculateDeliveryPrices();
        }
    }, 500);
    
    // Initialize product modal elements
    productModal = document.getElementById('productModal');
    closeProductModal = document.getElementById('closeProductModal');
    
    // Modal close buttons
    if (closeProductModal) {
        closeProductModal.addEventListener('click', closeProductModalFunc);
    }
    
    // Close modal on overlay click
    if (productModal) {
        productModal.addEventListener('click', (e) => {
            if (e.target === productModal) {
                closeProductModalFunc();
            }
        });
    }
    
    // Initialize cart sidebar elements
    cartSidebar = document.getElementById('cartSidebar');
    cartBtn = document.getElementById('cartBtn');
    closeCartSidebarBtn = document.getElementById('closeCartSidebar');
    cartOverlay = document.getElementById('cartOverlay');
    continueShoppingBtn = document.getElementById('continueShoppingBtn');
    
    // Cart button
    if (cartBtn) {
        cartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            openCartSidebar();
        });
    }
    
    // Close cart sidebar
    if (closeCartSidebarBtn) {
        closeCartSidebarBtn.addEventListener('click', closeCartSidebar);
    }
    
    // Close cart sidebar on overlay click
    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeCartSidebar);
    }
    
    // Continue shopping button
    if (continueShoppingBtn) {
        continueShoppingBtn.addEventListener('click', closeCartSidebar);
    }
    
    
    // Add to cart from modal
    const addToCartFromModal = document.getElementById('addToCartFromModal');
    if (addToCartFromModal) {
        addToCartFromModal.addEventListener('click', () => {
            const productCard = document.querySelector(`[data-product-id="${currentProductId}"]`);
            if (productCard) {
                const productId = productCard.dataset.productId;
                const productName = productCard.dataset.productName;
                const productPrice = productCard.dataset.productPrice;
                const productDescription = productCard.dataset.productDescription;
                addToCart(productId, productName, productPrice, productDescription);
                closeProductModalFunc();
            }
        });
    }
    
    // Checkout button in cart sidebar
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            if (cart.length > 0) {
                closeCartSidebar();
                openCheckoutModal();
            }
        });
    }
    
    // Close modals when clicking outside
    if (productModal) {
        productModal.addEventListener('click', (e) => {
            if (e.target === productModal) {
                closeProductModalFunc();
            }
        });
    }
    
});


// Delivery calculation and IP geolocation
let userCity = 'Москва';
let deliveryPrices = {
    express: 0,
    standard: 0
};

// Get user location by IP
async function getUserLocation() {
    const cityInput = document.getElementById('deliveryCity');
    if (!cityInput) return;
    
    // Show loading state
    cityInput.value = 'Определение города...';
    cityInput.disabled = true;
    
    try {
        const response = await fetch('https://reallyfreegeoip.org/json/');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        if (data.city && data.country_code === 'RU') {
            userCity = data.city;
            cityInput.value = userCity;
            calculateDeliveryPrices();
        } else {
            // Fallback to Moscow if not in Russia or city not detected
            userCity = 'Москва';
            cityInput.value = userCity;
            calculateDeliveryPrices();
        }
    } catch (error) {
        console.error('Error getting location:', error);
        userCity = 'Москва';
        cityInput.value = userCity;
        calculateDeliveryPrices();
        // Don't show error for CORS issues - fallback works fine
        if (!error.message.includes('CORS') && !error.message.includes('Failed to fetch')) {
            showErrorMessage('Не удалось определить город автоматически. Укажите город вручную.');
        }
    } finally {
        cityInput.disabled = false;
    }
}

// Calculate delivery prices based on distance from Moscow
function calculateDeliveryPrices() {
    // Moscow coordinates (approximate center)
    const moscowCoords = { lat: 55.7558, lng: 37.6173 };
    
    // Major Russian cities with approximate coordinates
    const cities = {
        'Москва': { lat: 55.7558, lng: 37.6173, distance: 0 },
        'Санкт-Петербург': { lat: 59.9343, lng: 30.3351, distance: 635 },
        'Новосибирск': { lat: 55.0084, lng: 82.9357, distance: 2810 },
        'Екатеринбург': { lat: 56.8431, lng: 60.6454, distance: 1417 },
        'Казань': { lat: 55.8304, lng: 49.0661, distance: 820 },
        'Нижний Новгород': { lat: 56.2965, lng: 43.9361, distance: 420 },
        'Челябинск': { lat: 55.1644, lng: 61.4368, distance: 1510 },
        'Самара': { lat: 53.2001, lng: 50.15, distance: 1050 },
        'Омск': { lat: 54.9885, lng: 73.3242, distance: 2240 },
        'Ростов-на-Дону': { lat: 47.2357, lng: 39.7015, distance: 1070 },
        'Уфа': { lat: 54.7388, lng: 55.9721, distance: 1167 },
        'Красноярск': { lat: 56.0184, lng: 92.8672, distance: 3350 },
        'Воронеж': { lat: 51.6720, lng: 39.1843, distance: 520 },
        'Пермь': { lat: 58.0105, lng: 56.2502, distance: 1150 },
        'Волгоград': { lat: 48.7194, lng: 44.5018, distance: 920 }
    };
    
    // Find city or estimate distance
    let distance = 0;
    const cityKey = Object.keys(cities).find(key => 
        userCity.toLowerCase().includes(key.toLowerCase()) || 
        key.toLowerCase().includes(userCity.toLowerCase())
    );
    
    if (cityKey) {
        distance = cities[cityKey].distance;
    } else {
        // Estimate based on city name similarity or default
        distance = 500; // Default for unknown cities
    }
    
    // Calculate prices based on distance (simplified CDEK-like pricing)
    // Base prices + distance multiplier
    const baseExpress = 500;
    const baseStandard = 300;
    const kmMultiplierExpress = 0.5;
    const kmMultiplierStandard = 0.3;
    
    deliveryPrices.express = Math.round(baseExpress + (distance * kmMultiplierExpress));
    deliveryPrices.standard = Math.round(baseStandard + (distance * kmMultiplierStandard));
    
    // Minimum prices
    if (distance === 0) {
        deliveryPrices.express = 0;
        deliveryPrices.standard = 0;
    } else {
        deliveryPrices.express = Math.max(deliveryPrices.express, 300);
        deliveryPrices.standard = Math.max(deliveryPrices.standard, 200);
    }
    
    // Update UI in checkout modal if needed
    const expressPriceEl = document.getElementById('expressPrice');
    const standardPriceEl = document.getElementById('standardPrice');
    
    if (expressPriceEl) {
        expressPriceEl.textContent = deliveryPrices.express > 0 
            ? `${deliveryPrices.express.toLocaleString('ru-RU')} ₽` 
            : 'Бесплатно';
    }
    
    if (standardPriceEl) {
        standardPriceEl.textContent = deliveryPrices.standard > 0 
            ? `${deliveryPrices.standard.toLocaleString('ru-RU')} ₽` 
            : 'Бесплатно';
    }
}


// Checkout Modal - Initialize after DOM is ready
let checkoutModal, closeCheckoutModal, checkoutForm;

function openCheckoutModal() {
    if (cart.length === 0) return;
    
    if (!checkoutModal) {
        checkoutModal = document.getElementById('checkoutModal');
    }
    if (!checkoutModal) return;
    
    closeCartSidebar();
    
    // Sync delivery type from cart sidebar
    const checkoutDeliveryType = document.querySelector(`input[name="deliveryType"][value="${selectedDeliveryType}"]`);
    if (checkoutDeliveryType) {
        checkoutDeliveryType.checked = true;
    }
    
    // Update checkout summary
    updateCheckoutSummary();
    
    // Set delivery type change handler
    const deliveryInputs = document.querySelectorAll('input[name="deliveryType"]');
    deliveryInputs.forEach(input => {
        input.removeEventListener('change', updateCheckoutSummary);
        input.addEventListener('change', () => {
            selectedDeliveryType = input.value;
            updateCheckoutSummary();
        });
    });
    
    checkoutModal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeCheckoutModalFunc() {
    if (!checkoutModal) {
        checkoutModal = document.getElementById('checkoutModal');
    }
    if (checkoutModal) {
        checkoutModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

function updateCheckoutSummary() {
    let subtotal = 0;
    cart.forEach(item => {
        const quantity = item.quantity || 1;
        subtotal += parseInt(item.price) * quantity;
    });
    
    const selectedDelivery = document.querySelector('input[name="deliveryType"]:checked');
    const deliveryPrice = selectedDelivery && selectedDelivery.value !== 'pickup' 
        ? (selectedDelivery.value === 'express' ? deliveryPrices.express : deliveryPrices.standard)
        : 0;
    
    const total = subtotal + deliveryPrice;
    
    const subtotalEl = document.getElementById('checkoutSubtotal');
    const deliveryEl = document.getElementById('checkoutDelivery');
    const totalEl = document.getElementById('checkoutTotal');
    
    if (subtotalEl) subtotalEl.textContent = `${subtotal.toLocaleString('ru-RU')} ₽`;
    if (deliveryEl) deliveryEl.textContent = deliveryPrice > 0 ? `${deliveryPrice.toLocaleString('ru-RU')} ₽` : 'Бесплатно';
    if (totalEl) totalEl.textContent = `${total.toLocaleString('ru-RU')} ₽`;
}

// Initialize checkout elements
checkoutModal = document.getElementById('checkoutModal');
closeCheckoutModal = document.getElementById('closeCheckoutModal');
checkoutForm = document.getElementById('checkoutForm');

// Checkout form submission with validation
if (checkoutForm) {
    checkoutForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Validate cart is not empty
        if (cart.length === 0) {
            showErrorMessage('Корзина пуста. Добавьте товары перед оформлением заказа.');
            closeCheckoutModalFunc();
            openCartSidebar();
            return;
        }
        
        // Validate form fields
        const fullName = checkoutForm.querySelector('input[name="fullName"]');
        const phone = checkoutForm.querySelector('input[name="phone"]');
        const email = checkoutForm.querySelector('input[name="email"]');
        const city = checkoutForm.querySelector('input[name="city"]');
        const street = checkoutForm.querySelector('input[name="street"]');
        const house = checkoutForm.querySelector('input[name="house"]');
        
        let isValid = true;
        let errorMessage = '';
        
        if (!fullName.value.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите ФИО';
            fullName.focus();
        } else if (!phone.value.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите телефон';
            phone.focus();
        } else if (!email.value.trim() || !email.validity.valid) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите корректный email';
            email.focus();
        } else if (!city.value.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите город';
            city.focus();
        } else if (!street.value.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите улицу';
            street.focus();
        } else if (!house.value.trim()) {
            isValid = false;
            errorMessage = 'Пожалуйста, укажите дом';
            house.focus();
        }
        
        if (!isValid) {
            showErrorMessage(errorMessage);
            return;
        }
        
        // Show loading state
        const submitBtn = checkoutForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Оформление заказа...';
        
        try {
            const formData = new FormData(checkoutForm);
            const orderData = {
                customer: {
                    fullName: formData.get('fullName'),
                    phone: formData.get('phone'),
                    email: formData.get('email')
                },
                address: {
                    city: formData.get('city'),
                    street: formData.get('street'),
                    house: formData.get('house'),
                    apartment: formData.get('apartment'),
                    comment: formData.get('deliveryComment')
                },
                delivery: {
                    type: formData.get('deliveryType'),
                    price: formData.get('deliveryType') === 'pickup' ? 0 : 
                           (formData.get('deliveryType') === 'express' ? deliveryPrices.express : deliveryPrices.standard)
                },
                items: cart,
                total: calculateOrderTotal()
            };
            
            // Here you would send orderData to your server/API
            console.log('Order submitted:', orderData);
            
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            const orderNumber = Math.floor(Math.random() * 10000);
            
            // Close modals first
            closeCheckoutModalFunc();
            
            // Clear cart
            cart = [];
            saveCart();
            
            // Reset form
            checkoutForm.reset();
            const cityInput = document.getElementById('deliveryCity');
            if (cityInput) {
                cityInput.value = userCity;
            }
            
            // Show success message after closing modal
            setTimeout(() => {
                showSuccessMessage('Заказ успешно оформлен! Номер заказа: #' + orderNumber);
            }, 300);
        } catch (error) {
            console.error('Error submitting checkout form:', error);
            showErrorMessage('Произошла ошибка при оформлении заказа. Попробуйте позже.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

function calculateOrderTotal() {
    let subtotal = 0;
    cart.forEach(item => {
        const quantity = item.quantity || 1;
        subtotal += parseInt(item.price) * quantity;
    });
    
    const selectedDelivery = document.querySelector('input[name="deliveryType"]:checked');
    const deliveryPrice = selectedDelivery && selectedDelivery.value !== 'pickup' 
        ? (selectedDelivery.value === 'express' ? deliveryPrices.express : deliveryPrices.standard)
        : 0;
    
    return subtotal + deliveryPrice;
}

// Initialize delivery calculation on page load
document.addEventListener('DOMContentLoaded', () => {
    getUserLocation();
    
    // Update delivery prices when city changes
    const cityInput = document.getElementById('deliveryCity');
    if (cityInput) {
        cityInput.addEventListener('change', () => {
            userCity = cityInput.value || 'Москва';
            calculateDeliveryPrices();
        });
    }
    
    // Cart delivery type change handler is handled by event delegation above
    
    // Initialize checkout elements if not already initialized
    if (!checkoutModal) checkoutModal = document.getElementById('checkoutModal');
    if (!closeCheckoutModal) closeCheckoutModal = document.getElementById('closeCheckoutModal');
    if (!checkoutForm) checkoutForm = document.getElementById('checkoutForm');
    
    // Close checkout modal
    if (closeCheckoutModal) {
        closeCheckoutModal.addEventListener('click', closeCheckoutModalFunc);
    }
    
    if (checkoutModal) {
        checkoutModal.addEventListener('click', (e) => {
            if (e.target === checkoutModal) {
                closeCheckoutModalFunc();
            }
        });
    }
});

