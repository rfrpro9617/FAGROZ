<?php
$title = $args['title'] ?? '';
$query_news = $args['query_news'] ?? [];

get_template_part('template-parts/components/post-preview-slider', null, [
  'title' => $title,
  'query_news' => $query_news,
]);
