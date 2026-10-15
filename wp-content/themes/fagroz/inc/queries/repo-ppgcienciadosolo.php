<?php

function get_teses_dissertacoes(string $ppg = 'Solos'): array
{
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
    $ppg
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
