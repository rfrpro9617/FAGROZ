<?php
$intro = get_field('intro') ?? '';
?>
<section class="section">
  <div class="container">
    <div class="departments__content">
      <div class="departments__text">
        <p class="departments__paragraph"><?php echo esc_html($intro); ?></p>
      </div>
    </div>
  </div>
</section>