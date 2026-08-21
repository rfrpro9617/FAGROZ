<?php
$title = $args['title'] ?? '';
$query_news = $args['query_news'] ?? [];
$show_tags = isset($args['show_tags']) ? (bool) $args['show_tags'] : true;

if (empty($query_news) || !is_array($query_news)) {
  return;
}

get_template_part(
  'template-parts/components/preview-slider',
  null,
  [
    'title' => $title,
    'query_news' => $query_news,
    'show_tags' => $show_tags,
  ]
);
