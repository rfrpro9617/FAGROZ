<?php
$intro = get_field('intro') ?? '';
?>
<section class="section">
  <div class="container">
    <div class="research__content">
      <div class="research__text">
        <p class="research__paragraph"><?php echo esc_html($intro); ?></p>
        <h2 class="research__title">Nossos Grupos de Pesquisa</h2>
      </div>
    </div>
  </div>
</section>