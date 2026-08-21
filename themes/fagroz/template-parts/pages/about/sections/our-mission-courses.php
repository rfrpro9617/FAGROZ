<?php
$mission_title = $args['mission_title'] ?? '';
$mission_description = $args['mission_description'] ?? '';
$courses_title = $args['courses_title'] ?? '';
$courses_description = $args['courses_description'] ?? '';
?>
<section class="page-sobre page-sobre__mission-courses">
  <div class="container page-sobre__layout page-sobre__mission-courses-layout">
    <div class="page-sobre__mission-courses-card">
      <span class="page-sobre__chip"><?php echo esc_html($mission_title); ?></span>
      <h2 class="page-sobre__title"><?php echo wp_kses_post($mission_description); ?></h2>
    </div>
    <div class="page-sobre__mission-courses-card">
      <span class="page-sobre__chip"><?php echo esc_html($courses_title); ?></span>
      <h2 class="page-sobre__title"><?php echo wp_kses_post($courses_description); ?></h2>
      <a class="button button--primary page-sobre__course-link" href="<?php echo site_url('/educacao') ?>">Explorar cursos</a>
    </div>
  </div>
</section>