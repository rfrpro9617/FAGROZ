
<?php
$acfHeroImage = get_field('hero_image');
$title = $acfHeroImage['title'] ?: get_the_title();
$bg_photo = $acfHeroImage['bg_photo'] ?? '';
if (is_array($bg_photo)) {
  $bg_photo = $bg_photo['url'] ?? '';
}
?>
<?php get_header(); ?>
<?php
get_template_part(
  'template-parts/components/hero/post-hero-image',
  null,
  [
    'title' => $title,
    'image' => $bg_photo,
    'text_position' => 'center',
  ]
);
?>
<?php get_template_part('template-parts/pages/departamentos/sections/intro'); ?>
<?php get_template_part('template-parts/pages/departamentos/sections/programs'); ?>
<?php get_footer(); ?>