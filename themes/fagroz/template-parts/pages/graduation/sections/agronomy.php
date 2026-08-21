<?php
$acfAgronomyGraduation = get_field('agronomy_graduation');
$content = $acfAgronomyGraduation['content'] ?? '';
$banner = $acfAgronomyGraduation['banner'] ?? '';
if (is_array($banner)) {
  $banner = $banner['url'] ?? '';
}
if (empty($banner)) {
  $banner = get_the_post_thumbnail_url(get_the_ID(), 'full');
}

$graduation = get_page_by_path('agronomia', OBJECT, 'graduation');
$graduation_link = $graduation ? get_permalink($graduation->ID) : home_url('/');
?>

<section>
  <div class="container graduacao-inner">
    <div class="graduacao-row">
      <div class="graduacao-text">
        <?php echo wp_kses_post($content); ?>
        <a
          href="<?php echo esc_url($graduation_link); ?>"
          class="btn-graduacao">
          Ver mais
        </a>
      </div>
      <div class="graduacao-image">
        <img src="<?php echo esc_url($banner); ?>" alt="Graduação" />
      </div>
    </div>
  </div>
</section>