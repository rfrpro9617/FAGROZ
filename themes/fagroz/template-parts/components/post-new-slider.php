<?php
$title = $args['title'] ?? '';
$query_news = $args['query_news'] ?? [];

if (empty($query_news) || !is_array($query_news)) {
  return;
}

get_template_part(
  'template-parts/components/new-slider',
  null,
  [
    'title' => $title,
    'query_news' => $query_news,
  ]
);
