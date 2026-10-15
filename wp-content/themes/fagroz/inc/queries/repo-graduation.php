<?php
if (!function_exists('fagroz_get_graduation_highlights')) {
  function fagroz_get_graduation_highlights(int $course_id = 0): array
  {
    $course_id = $course_id ?: get_the_ID();

    if (empty($course_id)) {
      return [];
    }

    $query = new WP_Query([
      'post_type'      => 'graduation-highlight',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'menu_order',
      'order'          => 'ASC',
      'meta_query'     => [
        [
          'key'     => 'course',
          'value'   => $course_id,
          'compare' => '=',
        ],
      ],
    ]);

    $highlights = [];

    while ($query->have_posts()) {
      $query->the_post();

      $id = get_the_ID();
      $category_meta = fagroz_get_post_category_meta($id, 'sem-categoria');

      $highlights[] = [
        'id' => $id,
        'title' => get_the_title($id),
        'permalink' => get_permalink($id),
        'thumbnail' => get_the_post_thumbnail_url($id, 'large'),
        'date' => get_the_date('j \d\e F \d\e Y', $id),
        'excerpt' => get_the_excerpt($id) ?: get_the_title($id),
        'label_text' => $category_meta['text'],
        'label_class' => $category_meta['class'],
      ];
    }

    wp_reset_postdata();

    return $highlights;
  }
}
