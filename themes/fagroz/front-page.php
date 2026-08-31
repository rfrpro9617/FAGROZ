<!--
  Página inicial do site (/)
-->
<?php get_header(); ?>
<?php
$acfSlider = get_field('slider');
$slides = [
  [
    'img' => $acfSlider['image_1'],
    'title' => $acfSlider['title_1'],
    'link' => $acfSlider['link_1'],
  ],
  [
    'img' => $acfSlider['image_2'],
    'title' => $acfSlider['title_2'],
    'link' => $acfSlider['link_2'],
  ],
  [
    'img' => $acfSlider['image_3'],
    'title' => $acfSlider['title_3'],
    'link' => $acfSlider['link_3'],
  ],
];
get_template_part(
  'template-parts/components/hero/post-hero-slider',
  null,
  ['slides' => $slides]
);
?>
<?php
$acfSearch = get_field('search');
$title = $acfSearch['title'];
$placeholder = $acfSearch['placeholder'];
get_template_part('template-parts/pages/home/sections/search', null, [
  'title' => $title,
  'placeholder' => $placeholder
]); ?>
<?php
$acfNews = get_field('news');
$title = $acfNews['title'];
$query_news = fagroz_get_home_news() ?? [];
get_template_part('template-parts/pages/home/sections/news', null, [
  'title' => $title,
  'query_news' => $query_news
]);
?>
<?php
get_template_part('template-parts/pages/home/sections/social-midida-news');
?>
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
<?php
$acfCollegeCampusLife = get_field('college_campus_life');
$title = $acfCollegeCampusLife['title'];
$description = $acfCollegeCampusLife['description'];
$bg_photo = $acfCollegeCampusLife['bg_photo'] ?? null;
if (is_array($bg_photo)) {
  $bg_photo = $bg_photo['url'] ?? '';
}
get_template_part(
  'template-parts/pages/home/sections/college-campus-life',
  null,
  [
    'title' => $title,
    'description' => $description,
    'bg_photo' => $bg_photo,
  ]
);
?>
<?php get_template_part('template-parts/pages/home/sections/where-we-are'); ?>
<?php get_footer(); ?>