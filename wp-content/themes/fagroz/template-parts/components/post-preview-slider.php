<?php
$title = $args['title'] ?? '';
$query_news = $args['query_news'] ?? [];
$show_tags = isset($args['show_tags']) ? (bool) $args['show_tags'] : true;
$use_btn_see_more = isset($args['use_btn_see_more']) ? (bool) $args['use_btn_see_more'] : true;

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
    'use_btn_see_more' => $use_btn_see_more,
  ]
);
