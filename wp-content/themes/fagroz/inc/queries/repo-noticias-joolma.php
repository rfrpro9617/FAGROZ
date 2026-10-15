<?php

function get_editor_mostras_permitidas(): array
{
  return [
    'agronomia',
    'pgsolos',
    'ppgfito',
    'ppgzoo',
    'ppgagro',
    'dplantaslav',
    'dplantasfor',
    'dfitossanidade',
    'estacao',
    'horti',
    'bib',
    'solos',
    'zootecnia',
    'nau',
    'nga',
  ];
}

function is_editor_mostra_valida(string $mostra): bool
{
  return in_array(trim($mostra), get_editor_mostras_permitidas(), true);
}

function get_editor_content(string $mostra = 'agronomia'): array
{
  $mostra = trim($mostra);

  if (!is_editor_mostra_valida($mostra)) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Categoria de conteúdo inválida.',
    ];
  }

  $db = get_programa_db();

  if (!$db instanceof wpdb) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os conteúdos.',
    ];
  }

  $sql = $db->prepare(
    "SELECT
      id,
      titulo,
      data,
      mostra,
      texto
    FROM editor
    WHERE mostra = %s
    ORDER BY data DESC, id DESC",
    $mostra
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log('Erro ao buscar os conteúdos: ' . $db->last_error);

    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os conteúdos.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'error' => false,
    'message' => '',
  ];
}
