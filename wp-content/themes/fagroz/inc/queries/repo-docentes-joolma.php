<?php

function get_docentes_deps_permitidos(): array
{
  return [
    'Solos',
    'Agronegocios',
    'Fitotecnia',
    'Zootecnia',
  ];
}

function is_docente_ppg_valido(string $ppg): bool
{
  return in_array(trim($ppg), get_docentes_deps_permitidos(), true);
}

function get_docentes_ppg(string $ppg = 'Solos'): array
{
  $ppg = trim($ppg);

  if (!is_docente_ppg_valido($ppg)) {
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
      'message' => 'Não foi possível carregar os docentes.',
    ];
  }

  $sql = $db->prepare(
    "SELECT
      NomeUsu,
      TitulaUsu,
      EmailUsu,
      FoneUsu,
      lattes,
      chavePrimaria
    FROM programa
    WHERE VinculoUsu = %s
      AND (PPG = %s OR PPG2 = %s)
    ORDER BY NomeUsu ASC",
    'Professor',
    $ppg,
    $ppg
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log('Erro ao buscar docentes do PPG: ' . $db->last_error);

    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os docentes.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'error' => false,
    'message' => '',
  ];
}

function get_docentes_deptos_permitidos(): array
{
  return [
    'Departamento de SOLOS',
    'Departamento de HORTICULTURA E SILVICULTURA',
    'Departamento de PLANTAS DE LAVOURA',
    'Departamento de ZOOTECNIA',
    'Departamento de PLANTAS FORRAGEIRAS E AGROMETEOROLOGIA',
    'Departamento de FITOSSANIDADE',
  ];
}

function is_docente_depto_valido(string $depto): bool
{
  return in_array(trim($depto), get_docentes_deptos_permitidos(), true);
}

function get_docentes_depto(string $depto = 'Departamento de SOLOS'): array
{
  $depto = trim($depto);

  if (!is_docente_depto_valido($depto)) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Departamento inválido.',
    ];
  }

  $db = get_programa_db();

  if (!$db instanceof wpdb) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os docentes.',
    ];
  }

  $sql = $db->prepare(
    "SELECT
      NomeUsu,
      TitulaUsu,
      EmailUsu,
      FoneUsu,
      lattes,
      chavePrimaria
    FROM programa
    WHERE VinculoUsu = %s
      AND DEPTO = %s
    ORDER BY NomeUsu ASC",
    'Professor',
    $depto
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log('Erro ao buscar docentes do Departamento ' . $depto . ': ' . $db->last_error);

    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os docentes.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'error' => false,
    'message' => '',
  ];
}
