<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Real-time TSF monitoring dashboard — Smart &amp; Sustainable Mining Lab, Laurentian University">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header" role="banner">
  <div class="header-inner">
    <div class="site-logo">
      <div class="logo-icon">TSF</div>
      <div>
        <div class="logo-text"><?php bloginfo('name'); ?></div>
        <div class="logo-sub">SSML · Laurentian University</div>
      </div>
    </div>
    <nav class="site-nav" role="navigation" aria-label="<?php esc_attr_e('Primary Menu','tsf-monitor'); ?>">
      <a href="#features">Features</a>
      <a href="#architecture">Architecture</a>
      <a href="#metrics">Outcomes</a>
      <a href="#about">About</a>
      <a href="https://github.com/rashed079/tsf-dashboard" class="nav-cta" target="_blank" rel="noopener">GitHub ↗</a>
    </nav>
  </div>
</header>
