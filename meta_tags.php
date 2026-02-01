<?php
/**
 * Генератор мета-тегов для SEO
 * Загружает настройки из seo.json и выводит мета-теги
 */

require_once 'config.php';
$seo = loadSEO();
?>
<!-- Primary Meta Tags -->
<title><?php echo htmlspecialchars($seo['title']); ?></title>
<meta name="title" content="<?php echo htmlspecialchars($seo['title']); ?>">
<meta name="description" content="<?php echo htmlspecialchars($seo['description']); ?>">
<?php if (!empty($seo['keywords'])): ?>
<meta name="keywords" content="<?php echo htmlspecialchars($seo['keywords']); ?>">
<?php endif; ?>
<?php if (!empty($seo['author'])): ?>
<meta name="author" content="<?php echo htmlspecialchars($seo['author']); ?>">
<?php endif; ?>
<meta name="robots" content="<?php echo htmlspecialchars($seo['robots']); ?>">
<?php if (!empty($seo['language'])): ?>
<meta name="language" content="<?php echo htmlspecialchars($seo['language']); ?>">
<?php endif; ?>
<?php if (!empty($seo['revisit_after'])): ?>
<meta name="revisit-after" content="<?php echo htmlspecialchars($seo['revisit_after']); ?>">
<?php endif; ?>
<?php if (!empty($seo['geo_region'])): ?>
<meta name="geo.region" content="<?php echo htmlspecialchars($seo['geo_region']); ?>">
<?php endif; ?>
<?php if (!empty($seo['geo_placename'])): ?>
<meta name="geo.placename" content="<?php echo htmlspecialchars($seo['geo_placename']); ?>">
<?php endif; ?>
<?php if (!empty($seo['geo_position'])): ?>
<meta name="geo.position" content="<?php echo htmlspecialchars($seo['geo_position']); ?>">
<meta name="ICBM" content="<?php echo htmlspecialchars(str_replace(';', ',', $seo['geo_position'])); ?>">
<?php endif; ?>

<!-- Open Graph / Facebook -->
<meta property="og:type" content="<?php echo htmlspecialchars($seo['og_type']); ?>">
<?php if (!empty($seo['og_url'])): ?>
<meta property="og:url" content="<?php echo htmlspecialchars($seo['og_url']); ?>">
<?php endif; ?>
<meta property="og:title" content="<?php echo htmlspecialchars($seo['og_title']); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($seo['og_description']); ?>">
<?php if (!empty($seo['og_image'])): ?>
<meta property="og:image" content="<?php echo htmlspecialchars($seo['og_image']); ?>">
<?php if (!empty($seo['og_image_width'])): ?>
<meta property="og:image:width" content="<?php echo htmlspecialchars($seo['og_image_width']); ?>">
<?php endif; ?>
<?php if (!empty($seo['og_image_height'])): ?>
<meta property="og:image:height" content="<?php echo htmlspecialchars($seo['og_image_height']); ?>">
<?php endif; ?>
<?php if (!empty($seo['og_image_alt'])): ?>
<meta property="og:image:alt" content="<?php echo htmlspecialchars($seo['og_image_alt']); ?>">
<?php endif; ?>
<?php endif; ?>
<?php if (!empty($seo['og_site_name'])): ?>
<meta property="og:site_name" content="<?php echo htmlspecialchars($seo['og_site_name']); ?>">
<?php endif; ?>

<!-- Twitter -->
<?php if (!empty($seo['twitter_card'])): ?>
<meta name="twitter:card" content="<?php echo htmlspecialchars($seo['twitter_card']); ?>">
<?php endif; ?>
<?php if (!empty($seo['twitter_title'])): ?>
<meta name="twitter:title" content="<?php echo htmlspecialchars($seo['twitter_title']); ?>">
<?php endif; ?>
<?php if (!empty($seo['twitter_description'])): ?>
<meta name="twitter:description" content="<?php echo htmlspecialchars($seo['twitter_description']); ?>">
<?php endif; ?>
<?php if (!empty($seo['twitter_image'])): ?>
<meta name="twitter:image" content="<?php echo htmlspecialchars($seo['twitter_image']); ?>">
<?php endif; ?>

<!-- Canonical URL -->
<?php if (!empty($seo['canonical_url'])): ?>
<link rel="canonical" href="<?php echo htmlspecialchars($seo['canonical_url']); ?>">
<?php endif; ?>

<!-- Favicon -->
<?php if (!empty($seo['favicon'])): ?>
<link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($seo['favicon']); ?>">
<?php endif; ?>

<!-- Search Engine Verification -->
<?php if (!empty($seo['google_verification'])): ?>
<meta name="google-site-verification" content="<?php echo htmlspecialchars($seo['google_verification']); ?>">
<?php endif; ?>
<?php if (!empty($seo['yandex_verification'])): ?>
<meta name="yandex-verification" content="<?php echo htmlspecialchars($seo['yandex_verification']); ?>">
<?php endif; ?>
<?php if (!empty($seo['vk_verification'])): ?>
<meta name="vk:verify" content="<?php echo htmlspecialchars($seo['vk_verification']); ?>">
<?php endif; ?>

<!-- VK (ВКонтакте) Meta Tags -->
<?php if (!empty($seo['og_image'])): ?>
<meta property="vk:image" content="<?php echo htmlspecialchars($seo['og_image']); ?>">
<?php endif; ?>
<?php if (!empty($seo['og_title'])): ?>
<meta property="vk:title" content="<?php echo htmlspecialchars($seo['og_title']); ?>">
<?php endif; ?>
<?php if (!empty($seo['og_description'])): ?>
<meta property="vk:description" content="<?php echo htmlspecialchars($seo['og_description']); ?>">
<?php endif; ?>
