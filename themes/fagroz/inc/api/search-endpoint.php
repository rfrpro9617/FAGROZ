<?php

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Retorna resultados de busca simplificados para o cabeçalho.
 * Usa transient para cachear resultados de buscas públicas repetidas.
 */
function fagroz_rest_search_results(WP_REST_Request $request)
{
  $term = $request->get_param('term');
  $term = trim(sanitize_text_field($term));

  if ($term === '') {
    return rest_ensure_response([]);
  }

  $cache_key = 'fagroz_search_' . md5($term);
  $results = get_transient($cache_key);

  if ($results !== false) {
    return rest_ensure_response($results);
  }

  $query = new WP_Query([
    'post_type' => 'post',
    's' => $term,
    'posts_per_page' => 10,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
  ]);

  $results = [];

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();

      $post_type = get_post_type();
      $post_type_object = get_post_type_object($post_type);

      $results[] = [
        'title' => get_the_title(),
        'link' => get_permalink(),
        'type' => $post_type,
        'typeLabel' => $post_type_object ? $post_type_object->labels->singular_name : '',
        'author' => get_the_author(),
        'excerpt' => wp_trim_words(get_the_excerpt(), 20),
        'orderby' => 'date',
        'order' => 'DESC',
      ];
    }

    wp_reset_postdata();
  }

  set_transient($cache_key, $results, MINUTE_IN_SECONDS * 5);

  return rest_ensure_response($results);
}

function fagroz_rest_search_permissions()
{
  return true;
}

function fagroz_register_rest_search_endpoint()
{
  register_rest_route('university/v1', 'search', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'fagroz_rest_search_results',
    'permission_callback' => 'fagroz_rest_search_permissions',
    'args' => [
      'term' => [
        'required' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'validate_callback' => function ($value) {
          return is_string($value) && trim($value) !== '';
        },
      ],
    ],
  ]);
}

add_action('rest_api_init', 'fagroz_register_rest_search_endpoint');
