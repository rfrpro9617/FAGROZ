<?php get_header(); ?>
<?php
$acfHeroImage = get_field('hero_image');
$title = $acfHeroImage['title'] ?: get_the_title();
$bg_photo = $acfHeroImage['bg_photo'] ?? '';
if (is_array($bg_photo)) {
  $bg_photo = $bg_photo['url'] ?? '';
}
if (empty($bg_photo)) {
  $bg_photo = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
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
<?php
$acfMembers = get_field('members');
$title = $acfMembers['title'] ?: '';
$content = $acfMembers['content'] ?: '';
get_template_part(
  'template-parts/pages/administracao/sections/members',
  null,
  [
    'title' => $title,
    'content' => $content,
  ]
);
?>
<?php
$title = get_field('title') ?: '';
$description = get_field('description') ?: '';
get_template_part(
  'template-parts/pages/administracao/sections/consuni',
  null,
  [
    'title' => $title,
    'description' => $description,
  ]
);
?>
<?php
$library_page = get_page_by_path('pagina-configuracao-secao-biblioteca');
$acfHeroImage = get_field('hero_image', $library_page->ID);
$title = $acfHeroImage['title'] ?? '';
$bg_photo = $acfHeroImage['bg_photo'] ?? null;
if (is_array($bg_photo)) {
  $bg_photo = $bg_photo['url'] ?? '';
}
$button_title = $acfHeroImage['button_title'] ?? '';
get_template_part(
  'template-parts/pages/home/sections/library',
  null,
  [
    'title' => $title,
    'bg_photo' => $bg_photo,
    'button_title' => $button_title,
  ]
);
?>
<?php get_template_part('template-parts/pages/administracao/sections/documentos'); ?>
<?php get_footer(); ?>