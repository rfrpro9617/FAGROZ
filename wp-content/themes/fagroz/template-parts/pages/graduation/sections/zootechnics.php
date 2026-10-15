<?php
$acfZootechnicsGraduation = get_field('zootechny_graduation');
$content = $acfZootechnicsGraduation['content'] ?? '';
$banner = $acfZootechnicsGraduation['banner'] ?? '';
$turno = $acfZootechnicsGraduation['turno'] ?? '';
$semestres = $acfZootechnicsGraduation['semestres'] ?? '';
if (is_array($banner)) {
  $banner = $banner['url'] ?? '';
}
if (empty($banner)) {
  $banner = get_the_post_thumbnail_url(get_the_ID(), 'full');
}

$graduation = get_page_by_path('zootecnia', OBJECT, 'graduation');
$graduation_link = $graduation ? get_permalink($graduation->ID) : home_url('/');
?>

<section class="graduacao-section">
  <div class="container graduacao-inner">
    <div class="graduacao-row">
      <div class="graduacao-image">
        <img src="<?php echo esc_url($banner); ?>" alt="Graduação" />
      </div>
      <div class="graduacao-text">
        <div class="graduacao-meta" aria-label="Informações do curso">
          <span class="graduacao-meta__tag"><?php echo esc_html($turno); ?></span>
          <span class="graduacao-meta__tag"><?php echo esc_html($semestres); ?></span>
        </div>
        <?php echo wp_kses_post($content); ?>
        <a
          href="<?php echo esc_url($graduation_link); ?>"
          class="btn-graduacao"
          <span class="dashicons dashicons-external"></span> Ver mais
        </a>
      </div>
    </div>
  </div>
</section>