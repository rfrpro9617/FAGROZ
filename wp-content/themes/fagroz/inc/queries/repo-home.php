<?php

if (!function_exists('fagroz_get_home_posts_data')) {
  function fagroz_get_home_posts_data(array $args = []): array
  {
    $paged = max(1, $args['paged'] ?? (get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1)));
    $order = isset($args['order']) && in_array(strtolower($args['order']), ['asc', 'desc'], true) ? strtoupper($args['order']) : 'DESC';
    $category = isset($args['category']) ? sanitize_text_field($args['category']) : 'all';
    $search = isset($args['search']) ? sanitize_text_field(wp_unslash($args['search'])) : '';
    $date = isset($args['date']) ? sanitize_text_field(wp_unslash($args['date'])) : '';

    $date_query = [];
    if ($date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
      $date_query = [[
        'year' => (int) substr($date, 0, 4),
        'month' => (int) substr($date, 5, 2),
        'day' => (int) substr($date, 8, 2),
      ]];
    }

    $home_query = new WP_Query([
      'post_type' => 'post',
      'post_status' => 'publish',
      'posts_per_page' => get_option('posts_per_page'),
      'paged' => $paged,
      'orderby' => 'date',
      'order' => $order,
      'category_name' => $category !== 'all' ? $category : '',
      's' => $search,
      'date_query' => $date_query,
    ]);

    $posts = [];

    if ($home_query->have_posts()) {
      while ($home_query->have_posts()) {
        $home_query->the_post();

        $post_id = get_the_ID();
        $category_meta = fagroz_get_post_category_meta($post_id, 'noticia');

        $posts[] = [
          'id' => $post_id,
          'title' => get_the_title($post_id),
          'permalink' => get_permalink($post_id),
          'thumbnail' => get_the_post_thumbnail_url($post_id, 'large') ?: get_template_directory_uri() . '/images/default-thumbnail.jpeg',
          'date' => get_the_date('j \d\e F \d\e Y', $post_id),
          'excerpt' => wp_trim_words(get_the_excerpt($post_id), 24),
          'label_text' => $category_meta['text'],
          'label_class' => $category_meta['class'],
        ];
      }

      wp_reset_postdata();
    }

    $pagination = paginate_links([
      'total' => $home_query->max_num_pages,
      'current' => $paged,
      'type' => 'list',
      'prev_text' => __('&laquo;'),
      'next_text' => __('&raquo;'),
      'add_args' => array_filter([
        'order' => strtolower($order),
        'category' => $category !== 'all' ? $category : 'all',
        'search' => $search !== '' ? $search : '',
        'date' => $date !== '' ? $date : '',
      ], static function ($value): bool {
        return $value !== '';
      }),
    ]);

    return [
      'query' => $home_query,
      'posts' => $posts,
      'pagination' => $pagination,
      'paged' => $paged,
      'order' => $order,
      'category' => $category,
      'search' => $search,
      'date' => $date,
    ];
  }
}
