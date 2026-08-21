<?php

/**
 * Template Part: Post Hero Slider
 *
 * Exibe o Hero Slider utilizando os argumentos recebidos.
 *
 * Args:
 * - slides (array): Lista de slides. Cada slide deve conter:
 *   - img (array|string): Imagem do slide ou array de imagem com URL.
 *   - title (string): Texto do slide.
 *   - llink (string): Link do slide.
 */

$slides = $args['slides'] ?? [];

if (empty($slides) || !is_array($slides)) {
  return;
}

get_template_part(
  'template-parts/components/hero/hero-slider',
  null,
  [
    'slides' => $slides,
  ]
);
