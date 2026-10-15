<?php
if (!function_exists('fagroz_get_post_category_meta')) {
  function fagroz_get_post_category_meta(int $post_id, string $fallback_slug = 'noticia'): array
  {
    $category_config = [
      'noticia' => [
        'text' => 'Notícia',
        'class' => 'preview-card__label--news',
      ],
      'atualizacao' => [
        'text' => 'Atualização',
        'class' => 'preview-card__label--update',
      ],
      'sem-categoria' => [
        'text' => 'Sem categoria',
        'class' => '',
      ],
    ];

    $categories = get_the_category($post_id);
    $slug = !empty($categories[0]->slug) ? $categories[0]->slug : $fallback_slug;

    return $category_config[$slug] ?? $category_config[$fallback_slug] ?? $category_config['sem-categoria'];
  }
}
if (!function_exists('fagroz_get_news_category_meta')) {
  function fagroz_get_news_category_meta(int $post_id): array
  {
    return fagroz_get_post_category_meta($post_id, 'noticia');
  }
}
