<?php
$title = $args['title'] ?? 'Título padrão';
$bg_photo = $args['bg_photo'] ?? '';
$button_title = $args['button_title'] ?? 'Acessar Biblioteca';
?>
<section id="biblioteca" class="library-section">
  <div class="library-section__bg" style="background-image: url('<?php echo esc_url($bg_photo); ?>');"></div>
  <div class="library-section__overlay"></div>

  <div class="library-section__container">
    <div class="library-section__content">
      <h2 class="library-section__title"><?php echo esc_html($title); ?></h2>

      <a href="<?php echo esc_url(FAGROZ_BIBAGRO_URL); ?>" class="library-section__button" target="_blank" rel="noopener noreferrer">
        <span class="dashicons dashicons-book-alt library-section__button-icon" aria-hidden="true"></span>
        <span><?php echo esc_html($button_title); ?></span>
      </a>
    </div>
  </div>
</section>