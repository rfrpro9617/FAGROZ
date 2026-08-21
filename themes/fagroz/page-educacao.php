<?php
$acfThumbnail = get_field('thumbnail');
$title = $acfThumbnail['title'] ?: get_the_title();
$photo = $acfThumbnail['photo'] ?? '';
if (is_array($photo)) {
  $photo = $photo['url'] ?? '';
}
if (empty($photo)) {
  $photo = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
?>
<?php get_header(); ?>
<?php
get_template_part(
  'template-parts/components/hero/post-hero-image',
  null,
  [
    'title' => $title,
    'image' => $photo,
  ]
);
?>
<?php get_template_part('template-parts/pages/education/sections/graduation'); ?>
<?php
$acfCollegeCampusLife = get_field('college_life');
$title = $acfCollegeCampusLife['title'] ?: get_the_title();
$description = $acfCollegeCampusLife['description'] ?: '';
$photo = $acfCollegeCampusLife['photo'] ?? '';
if (is_array($photo)) {
  $photo = $photo['url'] ?? '';
}
if (empty($photo)) {
  $photo = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
get_template_part(
  'template-parts/pages/home/sections/college-campus-life',
  null,
  [
    'title' => $title,
    'description' => $description,
    'bg_photo' => $photo,
    'compact_margin' => true,
  ]
);
?>
<?php get_template_part('template-parts/pages/education/sections/postgraduate'); ?>
<?php get_template_part('template-parts/pages/education/sections/research'); ?>
<?php get_footer(); ?>