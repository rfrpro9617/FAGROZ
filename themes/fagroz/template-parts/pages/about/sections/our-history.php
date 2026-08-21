<?php
$title = $args['title'] ?? '';
$column1 = $args['column_1'] ?? '';
$column2 = $args['column_2'] ?? '';
?>
<section id="sobre-nos" class="page-sobre page-sobre__nossa-historia">
  <div class="container page-sobre__layout">
    <h2 class="page-sobre__title"><?php echo esc_html($title); ?></h2>
    <div class="page-sobre__content">
      <div class="page-sobre__content-column">
        <p><?php echo wp_kses_post($column1); ?></p>
      </div>
      <div class="page-sobre__content-column">
        <p><?php echo wp_kses_post($column2); ?></p>
      </div>
    </div>
  </div>
</section>