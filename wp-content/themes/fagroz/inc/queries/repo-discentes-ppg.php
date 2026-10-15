<?php

function get_discentes_ppgs_permitidos(): array
{
  return [
    'Solos',
    'Agronegocios',
    'Fitotecnia',
    'Zootecnia',
  ];
}

function is_discente_ppg_valido(string $ppg): bool
{
  return in_array(trim($ppg), get_discentes_ppgs_permitidos(), true);
}

function get_discentes_ppg(string $ppg = 'Solos'): array
{
  $ppg = trim($ppg);

  if (!is_discente_ppg_valido($ppg)) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Programa de pós-graduação inválido.',
    ];
  }

  $db = get_programa_db();

  if (!$db instanceof wpdb) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os discentes.',
    ];
  }

  $sql = $db->prepare(
    "SELECT
      NomeUsu,
      NivelAluno,
      Orientador
    FROM programa
    WHERE VinculoUsu = %s
      AND PPG = %s
    ORDER BY NomeUsu ASC",
    'Aluno',
    $ppg
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log('Erro ao buscar discentes do PPG: ' . $db->last_error);

    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os discentes.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'error' => false,
    'message' => '',
  ];
}