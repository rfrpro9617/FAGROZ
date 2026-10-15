<?php

function get_publicacoes_grupos_permitidos(): array
{
  return [
    'GSR',
    'Ecologia de Pastejo',
    'Borbulheira de Citros',
    'Fisiologia Pos-Colheita',
    'NESPRO',
    'Substrato',
    'GRUPEX',
  ];
}

function is_publicacao_grupo_valido(string $grupo): bool
{
  return in_array(trim($grupo), get_publicacoes_grupos_permitidos(), true);
}

function get_publicacoes_grupo(string $grupo, string $tipo = ''): array
{
  if (!is_publicacao_grupo_valido($grupo)) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Grupo de pesquisa inválido.',
    ];
  }

  $db = get_programa_db();

  if (!$db instanceof wpdb) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar as publicações do grupo.',
    ];
  }

  $sql = $db->prepare(
    "SELECT
      TeseTitulo,
      TeseAluno,
      TeseAno,
      TeseAnexa,
      Identifica,
      chavePrimaria
    FROM programa
    WHERE TeseTitulo != %s
      AND TipoConteudo LIKE %s
      AND TipoConteudo NOT IN (%s, %s, %s)
      AND PPG LIKE %s
      AND grupoPesq LIKE %s
    ORDER BY TeseAno DESC, TeseAluno ASC",
    '0',
    '%' . trim($tipo) . '%',
    'Imagem',
    'Download',
    'Video',
    '%',
    trim($grupo)
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log('Erro ao buscar publicações do grupo: ' . $db->last_error);

    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar as publicações do grupo.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'error' => false,
    'message' => '',
  ];
}
