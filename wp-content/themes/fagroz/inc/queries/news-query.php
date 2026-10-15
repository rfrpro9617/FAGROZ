<?php

if (!function_exists('fagroz_get_home_news')) {
  function fagroz_get_home_news(int $posts_per_page = null): array
  {
    $posts_per_page = $posts_per_page ?? (int) get_option('posts_per_page');
    $news_query = new WP_Query([
      'post_type' => 'post',
      'posts_per_page' => $posts_per_page,
      'post_status' => 'publish',
    ]);
    $news = [];

    if ($news_query->have_posts()) {
      while ($news_query->have_posts()) {
        $news_query->the_post();
        $post_id = get_the_ID();
        $category_config = fagroz_get_news_category_meta($post_id);
        $news[] = [
          'id' => $post_id,
          'title' => get_the_title($post_id),
          'permalink' => get_permalink($post_id),
          'thumbnail' => get_the_post_thumbnail_url($post_id, 'large') ?: get_template_directory_uri() . '/images/default-thumbnail.jpeg',
          'date' => get_the_date('j \d\e F \d\e Y', $post_id),
          'excerpt' => wp_trim_words(get_the_excerpt($post_id), 20),
          'label_text' => $category_config['text'],
          'label_class' => $category_config['class'],
        ];
      }
      wp_reset_postdata();
    }
    return $news;
  }
}
