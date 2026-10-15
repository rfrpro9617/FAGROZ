<?php
if (!function_exists('fagroz_get_centers')) {
  function fagroz_get_centers(array $args = []): array
  {
    $paged = max(1, $args['paged'] ?? (get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1)));
    $search = isset($args['search']) ? sanitize_text_field(wp_unslash((string) $args['search'])) : '';
    $search = trim($search);

    $query_args = [
      'post_type' => 'nucleos',
      'posts_per_page' => 4,
      'paged' => $paged,
      'post_status' => 'publish',
      'orderby' => 'title',
      'order' => 'ASC',
    ];

    if ($search !== '') {
      $query_args['s'] = $search;
    }

    $query = new WP_Query($query_args);

    $items = [];

    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();

        $items[] = [
          'id' => $post_id,
          'title' => get_the_title($post_id),
          'permalink' => get_permalink($post_id),
          'thumbnail' => get_the_post_thumbnail_url($post_id, 'large'),
          'excerpt' => get_the_excerpt($post_id),
        ];
      }

      wp_reset_postdata();
    }

    $pagination_base = get_pagenum_link(1);
    $pagination_base = remove_query_arg('paged', $pagination_base);
    $pagination_base = add_query_arg('paged', '%#%', $pagination_base);

    $pagination = paginate_links([
      'base' => $pagination_base,
      'format' => '',
      'add_args' => $search !== '' ? ['nucleos_search' => $search] : [],
      'total' => $query->max_num_pages,
      'current' => $paged,
      'type' => 'list',
      'prev_text' => __('&laquo;'),
      'next_text' => __('&raquo;'),
    ]);

    return [
      'items' => $items,
      'pagination' => $pagination,
      'paged' => $paged,
      'query' => $query,
      'search' => $search,
    ];
  }
}
