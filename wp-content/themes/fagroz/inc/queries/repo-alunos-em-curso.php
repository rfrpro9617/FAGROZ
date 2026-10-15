<?php

function get_alunos_em_curso_cursos_permitidos(): array
{
  return [
    'AGRONOMIA',
    'ZOOTECNIA',
  ];
}

function is_alunos_em_curso_valido(string $curso): bool
{
  return in_array(strtoupper(trim($curso)), get_alunos_em_curso_cursos_permitidos(), true);
}

function get_alunos_em_curso(string $curso = 'AGRONOMIA'): array
{
  $curso = strtoupper(trim($curso));

  if (!is_alunos_em_curso_valido($curso)) {
    return [
      'items' => [],
      'total' => 0,
      'error' => true,
      'message' => 'Curso inválido. Informe AGRONOMIA ou ZOOTECNIA.',
    ];
  }

  $db = get_programa_db();

  if (!$db instanceof wpdb) {
    return [
      'items' => [],
      'total' => 0,
      'error' => true,
      'message' => 'Não foi possível carregar os alunos em curso.',
    ];
  }

  $sql = $db->prepare(
    "SELECT NomeUsu
    FROM programa
    WHERE VinculoUsu = %s
      AND (PPG = %s OR PPG = %s)
      AND emCursoGR = %s
    ORDER BY NomeUsu ASC",
    'Aluno',
    '0',
    '',
    $curso
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log('Erro ao buscar alunos em curso: ' . $db->last_error);

    return [
      'items' => [],
      'total' => 0,
      'error' => true,
      'message' => 'Não foi possível carregar os alunos em curso.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'total' => count($resultados ?: []),
    'error' => false,
    'message' => '',
  ];
}
