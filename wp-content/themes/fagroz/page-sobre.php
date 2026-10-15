<?php get_header(); ?>
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
get_template_part(
  'template-parts/components/hero/post-hero-image',
  null,
  [
    'title' => $title,
    'image' => $photo,
    'text_position' => 'center',
  ]
);
?>
<?php
$acfOurHistory = get_field('our_history');
$title = $acfOurHistory['title'] ?? '';
$column1 = $acfOurHistory['column_1'] ?? '';
$column2 = $acfOurHistory['column_2'] ?? '';
get_template_part(
  'template-parts/pages/about/sections/our-history',
  null,
  [
    'title' => $title,
    'column_1' => $column1,
    'column_2' => $column2,
  ]
);
?>
<?php
$acfOurMission = get_field('our_mission');
$our_mission_title = $acfOurMission['title'] ?? '';
$our_mission_description = $acfOurMission['description'] ?? '';
$acfOurCourses = get_field('our_courses');
$our_courses_title = $acfOurCourses['title'] ?? '';
$our_courses_description = $acfOurCourses['description'] ?? '';
get_template_part(
  'template-parts/pages/about/sections/our-mission-courses',
  null,
  [
    'mission_title' => $our_mission_title,
    'mission_description' => $our_mission_description,
    'courses_title' => $our_courses_title,
    'courses_description' => $our_courses_description,
  ]
);
?>
<?php get_template_part('template-parts/pages/about/sections/our-centers'); ?>
<?php get_template_part('template-parts/pages/about/sections/drive-fagroz'); ?>
<?php get_template_part('template-parts/pages/home/sections/where-we-are'); ?>
<?php get_footer(); ?>