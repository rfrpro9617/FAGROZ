<?php

function get_teses_dissertacoes_ppgs_permitidos(): array
{
  return [
    'Solos',
    'Agronegocios',
    'Fitotecnia',
    'Zootecnia',
  ];
}

function is_ppg_valido(string $ppg): bool
{
  return in_array(trim($ppg), get_teses_dissertacoes_ppgs_permitidos(), true);
}

function get_teses_dissertacoes(string $ppg = 'Solos'): array
{
  if (!is_ppg_valido($ppg)) {
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
      'message' => 'Não foi possível carregar as teses e dissertações.',
    ];
  }

  $sql = $db->prepare(
    "SELECT
      TeseTitulo,
      TeseAluno,
      TeseAno,
      TeseAnexa,
      Identifica,
      chavePrimaria,
      PPG
    FROM programa
    WHERE TeseTitulo != %s
      AND TipoConteudo = %s
      AND PPG = %s
    ORDER BY TeseAno DESC, TeseAluno ASC",
    '0',
    'Tese/Dissertacao',
    trim($ppg)
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log(
      'Erro ao buscar teses e dissertações: ' . $db->last_error
    );

    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar as teses e dissertações.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'error' => false,
    'message' => '',
  ];
}
