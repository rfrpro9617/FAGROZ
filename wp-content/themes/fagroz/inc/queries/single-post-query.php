<?php

if (!function_exists('fagroz_get_the_tags')) {
  function fagroz_get_the_tags(int $post_id = 0): array
  {
    $post_id = $post_id ?: get_the_ID();

    if (empty($post_id)) {
      return [];
    }

    if (get_post_type($post_id) === 'graduation-highlight') {
      $tags = get_the_terms($post_id, 'post_tag');

      if (is_wp_error($tags) || empty($tags)) {
        return [];
      }

      return array_values($tags);
    }

    $tags = get_the_tags($post_id);

    return is_array($tags) ? $tags : [];
  }
}

if (!function_exists('fagroz_get_related_posts')) {
  function fagroz_get_related_posts(int $post_id, array $tag_ids = [], string $post_type = 'post'): array
  {
    $query_args = [
      'post_type' => $post_type,
      'posts_per_page' => 2,
      'post__not_in' => [$post_id],
      'ignore_sticky_posts' => true,
    ];

    if (!empty($tag_ids)) {
      $query_args['tag__in'] = $tag_ids;
    } else {
      $query_args['orderby'] = 'date';
      $query_args['order'] = 'DESC';
    }

    $related_query = new WP_Query($query_args);

    if (!empty($tag_ids) && !$related_query->have_posts()) {
      $fallback_args = [
        'post_type' => $post_type,
        'posts_per_page' => 2,
        'post__not_in' => [$post_id],
        'ignore_sticky_posts' => true,
        'orderby' => 'date',
        'order' => 'DESC',
      ];

      $related_query = new WP_Query($fallback_args);
    }

    $related_posts = [];

    if ($related_query->have_posts()) {
      while ($related_query->have_posts()) {
        $related_query->the_post();

        $related_post_id = get_the_ID();
        $category_meta = fagroz_get_post_category_meta($related_post_id, 'noticia');

        $related_posts[] = [
          'id' => $related_post_id,
          'title' => get_the_title($related_post_id),
          'permalink' => get_permalink($related_post_id),
          'thumbnail' => get_the_post_thumbnail_url($related_post_id, 'large') ?: get_template_directory_uri() . '/images/default-thumbnail.jpeg',
          'date' => get_the_date('j \d\e F', $related_post_id),
          'excerpt' => get_the_excerpt($related_post_id),
          'label_text' => $category_meta['text'],
          'label_class' => $category_meta['class'],
        ];
      }

      wp_reset_postdata();
    }

    return $related_posts;
  }
}
