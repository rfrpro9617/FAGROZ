<?php
$acfPostGraduate = get_field('postgraduate');
$content = $acfPostGraduate['content']  ?? '';
$banner = $acfPostGraduate['banner'] ?? '';
if (is_array($banner)) {
  $banner = $banner['url'] ?? '';
}
if (empty($banner)) {
  $banner = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
$postgraduatePage = get_page_by_path('educacao/pos-graduacao');
$postgraduateUrl = $postgraduatePage ? get_permalink($postgraduatePage) : site_url('/educacao/pos-graduacao');
?>
<section id="pos-graduacao" class="education-section">
  <div class="container education-inner">
    <div class="education-row">
      <div class="education-image">
        <img src="<?php echo esc_url($banner); ?>" alt="Pós-Graduação" />
      </div>
      <div class="education-text">
        <?php echo wp_kses_post($content); ?>
        <a href="<?php echo esc_url($postgraduateUrl); ?>" class="btn-education">Ver mais</a>
      </div>
    </div>
  </div>
</section>