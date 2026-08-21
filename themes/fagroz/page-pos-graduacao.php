<?php
$thumbnail = get_field('thumbnail');
$title = $thumbnail['title'] ?: get_the_title();
$photo = $thumbnail['photo'] ?? '';
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
$parent_id = wp_get_post_parent_id(get_the_ID());
if (empty($parent_id)) {
  $parent = get_page_by_path('educacao');
  $parent_id = $parent ? $parent->ID : 0;
}
?>
<div class="container container--single page-section">
  <?php get_template_part(
    'template-parts/components/breadcrumbs',
    null,
    [
      'items' => [
        [
          'label' => 'Educação',
          'url' => $parent_id ? get_permalink($parent_id) : home_url('/'),
        ],
        [
          'label' => get_the_title(),
          'url' => '',
        ],
      ],
    ]
  ); ?>
</div>
<?php get_template_part('template-parts/pages/postgraduate/sections/programs'); ?>
<?php
$callTeachingOpportunities = get_page_by_path('pagina-configuracao-chamada-para-oportunidades-de-ensino');
$title = get_field('title', $callTeachingOpportunities->ID);
$description = get_field('description', $callTeachingOpportunities->ID);
$button_title = get_field('button_title', $callTeachingOpportunities->ID);
get_template_part(
  'template-parts/pages/home/sections/great-college-opportunities',
  null,
  [
    'title' => $title,
    'description' => $description,
    'button_title' => $button_title,
    'show_button' => false,
  ]
);
?>
<?php get_footer(); ?>