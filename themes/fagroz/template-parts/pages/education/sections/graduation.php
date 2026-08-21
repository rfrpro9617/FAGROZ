<?php
$acfGraduation = get_field('graduation');
$content = $acfGraduation['content']  ?? '';
$banner = $acfGraduation['banner'] ?? '';
if (is_array($banner)) {
  $banner = $banner['url'] ?? '';
}
if (empty($banner)) {
  $banner = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
$graduationPage = get_page_by_path('educacao/graduacao');
$graduationUrl = $graduationPage ? get_permalink($graduationPage) : site_url('/educacao/graduacao');
?>

<section id="graduacao" class="education-section">
  <div class="container education-inner">
    <div class="education-row">
      <div class="education-text">
        <?php echo wp_kses_post($content); ?>
        <a href="<?php echo esc_url($graduationUrl); ?>" class="btn-education">Ver mais</a>
      </div>
      <div class="education-image">
        <img src="<?php echo esc_url($banner); ?>" alt="Graduação" />
      </div>
    </div>
  </div>
</section>