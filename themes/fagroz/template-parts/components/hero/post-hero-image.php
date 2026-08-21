<?php

/**
 * Template Part: Post Hero
 *
 * Exibe o Hero utilizando os argumentos recebidos.
 *
 * Args:
 * - title (string): Título do Hero.
 * - image (string): URL da imagem de fundo.
 * - bg_photo (string): URL da imagem de fundo, quando usada pelo ACF.
 */

$title = $args['title'] ?? '';
$image = $args['bg_photo'] ?? $args['image'] ?? '';
$textPosition = $args['text_position'] ?? 'bottom-left';
$compactMargin = $args['compact_margin'] ?? false;

if (empty($title) && empty($image)) {
  return;
}

get_template_part(
  'template-parts/components/hero/hero-image',
  null,
  [
    'title' => $title,
    'image' => $image,
    'text_position' => $textPosition,
    'compact_margin' => $compactMargin,
  ]
);
