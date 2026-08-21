<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <div class="top-bar">
    <div class="container">
      <div class="top-bar__content">
        <a href="<?php echo esc_url(FAGROZ_UFRGS_URL); ?>" target="_blank" class="top-link">UFRGS</a>
        <a href="<?php echo esc_url(FAGROZ_INTRANET_URL); ?>" target="_blank" class="top-link">Intranet</a>
      </div>
    </div>
  </div>
  <header class="header-main">
    <div class="container">
      <div class="header-main__content">
        <div class="logo">
          <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="Página inicial FAGROZ">
            <span class="logo-text">FAGROZ</span>
            <span class="logo-subtext">UFRGS</span>
          </a>
        </div>
        <button
          class="menu-toggle"
          aria-label="Abrir menu"
          aria-expanded="false"
          aria-controls="main-menu">
          <span class="dashicons dashicons-menu-alt3"></span>
        </button>
        <div class="nav-search-group">
          <nav
            aria-label="Menu principal"
            class="main-nav">
            <?php
            wp_nav_menu([
              'theme_location' => 'headerMenuLocation',
              // Define se o wp deve envolver o menu em uma div ou não. Se false, não envolve.
              'container' => false,
              // Define a classe do menu. Se não definido, o wp define uma classe padrão.
              'menu_class' => 'menu',
              'menu_id' => 'main-menu',
              // Define o que acontece se o menu não for encontrado. Se false, não exibe nada. Se true, exibe uma lista de páginas. Se 'fallback_cb' => false, não exibe nada.
              'fallback_cb' => false,
              'depth' => 2,
              'walker' => new Fagroz_Mega_Menu_Walker(),
            ]);
            ?>
          </nav>
          <div class="header-actions">
            <button class="js-search-trigger site-header__search-trigger search-btn" aria-label="Pesquisar" type="button">
              <span class="iconc-search dashicons dashicons-search"></span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>