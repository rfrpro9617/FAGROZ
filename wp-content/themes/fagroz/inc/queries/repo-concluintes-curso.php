<?php

function get_concluintes_cursos_permitidos(): array
{
  return [
    'AGRONOMIA',
    'ZOOTECNIA',
  ];
}

function is_concluinte_curso_valido(string $curso): bool
{
  return in_array(strtoupper(trim($curso)), get_concluintes_cursos_permitidos(), true);
}

function get_concluintes_curso(string $curso = 'AGRONOMIA'): array
{
  $curso = strtoupper(trim($curso));

  if (!is_concluinte_curso_valido($curso)) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Curso inválido. Informe AGRONOMIA ou ZOOTECNIA.',
    ];
  }

  $db = get_programa_db();

  if (!$db instanceof wpdb) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os concluintes do curso.',
    ];
  }

  $sql = $db->prepare(
    "SELECT
      concluiuCurso,
      NomeUsu
    FROM programa
    WHERE concluiuCurso <> %s
      AND cursoConcluiu = %s
    ORDER BY concluiuCurso DESC, NomeUsu ASC",
    '0',
    $curso
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log('Erro ao buscar concluintes do curso: ' . $db->last_error);

    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os concluintes do curso.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'error' => false,
    'message' => '',
  ];
}
