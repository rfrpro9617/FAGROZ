<?php
$consuni_page = get_page_by_path('administracao/consuni');
$consuni_page_url = $consuni_page ? get_permalink($consuni_page) : site_url('/administracao/consuni');
$title = $args['title'] ?? '';
$description = $args['description'] ?? '';
?>

<section class="consuni" aria-labelledby="consuni-title">
  <div class="container">
    <div class="consuni__content">
      <header class="consuni__header">
        <h2 id="consuni-title" class="consuni__title"><?php echo esc_html($title); ?></h2>
        <p class="consuni__description">
          <?php echo wp_kses_post($description); ?>
        </p>
      </header>

      <div class="consuni__toolbar">
        <h3 class="consuni__subtitle">
          <a href="<?php echo esc_url($consuni_page_url); ?>" target="_blank">Conheça o conselho</a>
        </h3>
      </div>
    </div>
  </div>
</section>