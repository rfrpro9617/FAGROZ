<?php
$intro = get_field('intro') ?? '';
$title = get_field('title') ?? '';
?>
<section class="section">
  <div class="container">
    <div class="research__content">
      <div class="research__text">
        <span class="research__paragraph"><?php echo wp_kses_post($intro); ?></span>
        <h2 class="research__title"><?php echo esc_html($title); ?></h2>
      </div>
    </div>
  </div>
</section>