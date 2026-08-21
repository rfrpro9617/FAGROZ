<?php
if (!function_exists('fagroz_resolve_documento_url')) {
  function fagroz_resolve_documento_url(int $post_id): string
  {
    $document_url = '';

    if (function_exists('get_field')) {
      $field_candidates = ['documento', 'arquivo', 'document_file'];

      foreach ($field_candidates as $field_name) {
        $field_value = get_field($field_name, $post_id);

        if (!empty($field_value)) {
          if (is_array($field_value) && !empty($field_value['url'])) {
            $document_url = $field_value['url'];
          } elseif (is_numeric($field_value)) {
            $document_url = wp_get_attachment_url((int) $field_value);
          } elseif (is_string($field_value)) {
            $document_url = $field_value;
          }

          if (!empty($document_url)) {
            break;
          }
        }
      }
    }

    if (empty($document_url)) {
      $attachments = get_posts([
        'post_type' => 'attachment',
        'post_parent' => $post_id,
        'posts_per_page' => 20,
        'post_status' => 'inherit',
        'orderby' => 'menu_order ID',
        'order' => 'ASC',
        'suppress_filters' => false,
      ]);

      if (!empty($attachments)) {
        foreach ($attachments as $attachment) {
          $attachment_url = wp_get_attachment_url($attachment->ID);
          if (!empty($attachment_url)) {
            $mime_type = $attachment->post_mime_type ?? '';
            if ($mime_type !== '' && strpos($mime_type, 'image/') !== 0) {
              $document_url = $attachment_url;
              break;
            }

            if (empty($document_url)) {
              $document_url = $attachment_url;
            }
          }
        }
      }
    }

    return $document_url ?: get_permalink($post_id);
  }
}

if (!function_exists('fagroz_get_documentos_items')) {
  function fagroz_get_documentos_items(array $args = []): array
  {
    $paged = max(1, $args['paged'] ?? (get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1)));
    $search_term = $args['search'] ?? (isset($_GET['documentos_search']) ? sanitize_text_field(wp_unslash($_GET['documentos_search'])) : '');

    $query_args = [
      'post_type'      => 'documentos',
      'posts_per_page' => 4,
      'paged'          => $paged,
      'post_status'    => 'publish',
      'orderby'        => 'menu_order',
      'order'          => 'ASC',
      'suppress_filters' => false,
    ];

    if ($search_term !== '') {
      $query_args['s'] = $search_term;
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
          'document_url' => fagroz_resolve_documento_url($post_id),
          'thumbnail' => get_the_post_thumbnail_url($post_id, 'large'),
          'excerpt' => get_the_excerpt($post_id),
        ];
      }

      wp_reset_postdata();
    }

    $pagination = '';

    if ($query->max_num_pages > 1) {
      $pagination = paginate_links([
        'base' => add_query_arg('paged', '%#%'),
        'format' => '',
        'current' => $paged,
        'total' => $query->max_num_pages,
        'type' => 'list',
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'add_args' => $search_term !== '' ? ['documentos_search' => $search_term] : [],
      ]);
    }

    return [
      'items' => $items,
      'pagination' => $pagination,
      'paged' => $paged,
      'query' => $query,
      'search' => $search_term,
    ];
  }
}