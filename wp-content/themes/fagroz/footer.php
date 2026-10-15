<?php
$footer_page = get_page_by_path('pagina-configuracao-footer');
$footer = get_field('footer', $footer_page->ID);
$address = $footer['address'] ?? '';
$cellphone = $footer['cellphone'] ?? '';
$email = $footer['email'] ?? '';
?>
<footer class="site-footer">
  <div class="site-footer__inner container">
    <div class="site-footer__content">
      <div class="site-footer__col-logo">
        <div class="site-footer__logo">
          <h1 class="site-footer__logo-text">
            <a href="<?php echo site_url() ?>">
              <img src="<?php echo esc_url(get_template_directory_uri() . '/images/logo-fagroz.png'); ?>" alt="Logo FAGROZ" class="site-footer__logo-image">
            </a>
          </h1>
        </div>
      </div>
      <div class="site-footer__col-access">
        <h3 class="site-footer__heading">Acesso rápido</h3>
        <nav class="site-footer__nav">
          <ul>
            <li><a href="<?php echo site_url('/noticias') ?>">Notícias</a></li>
            <li><a href="<?php echo site_url('/docentes') ?>">Docentes</a></li>
            <li><a href="<?php echo esc_url(FAGROZ_INTRANET_URL); ?>">Intranet</a></li>
          </ul>
        </nav>
      </div>
      <div class="site-footer__col-contact">
        <h3 class="site-footer__heading">Contatos</h3>
        <div class="site-footer__contact-info">
          <p class="site-footer__address">
            <?php echo nl2br(esc_html($address)); ?>
          </p>
          <p class="site-footer__phone">
            <a href="tel:<?php echo esc_attr($cellphone); ?>"><?php echo esc_html($cellphone); ?></a>
          </p>
          <p class="site-footer__email">
            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
          </p>
          <p class="site-footer__about">
            <a href="<?php echo site_url('/sobre') ?>">Quem somos?</a>
          </p>
        </div>
      </div>
      <div class="site-footer__col-social">
        <nav class="site-footer__social">
          <ul class="site-footer__social-list">
            <li><a href="<?php echo esc_url(FAGROZ_YOUTUBE_URL); ?>" class="site-footer__social-link site-footer__social-link--youtube" title="YouTube"><span class="dashicons dashicons-youtube"></span></a></li>
            <li><a href="#" class="site-footer__social-link site-footer__social-link--facebook" title="Facebook"><span class="dashicons dashicons-facebook"></span></a></li>
            <li><a href="#" class="site-footer__social-link site-footer__social-link--instagram" title="Instagram"><span class="dashicons dashicons-instagram"></span></a></li>
          </ul>
        </nav>
      </div>
    </div>
    <div class="site-footer__bottom">
      <p class="site-footer__copyright">
        Todos os direitos autorais reservados. Rodrigo Ferreira Rodrigues 2026.
      </p>
    </div>
  </div>
</footer>
<div class="search-overlay">
  <div class="search-overlay__top">
    <div class="container">
      <input type="text" class="search-term" placeholder="Escreva aqui para encontrar notícias" id="search-term" autocomplete="off">
      <span class="dashicons dashicons-no-alt search-overlay__close" aria-hidden="true"></span>
    </div>
  </div>
  <div class="container">
    <div id="search-overlay__results"></div>
  </div>
</div>
<?php wp_footer(); ?>
</body>

</html>