<?php

function get_tecnico_administrativo_deptos_permitidos(): array
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

function is_tecnico_administrativo_depto_valido(string $depto): bool
{
  return in_array(trim($depto), get_tecnico_administrativo_deptos_permitidos(), true);
}

function get_tecnicos_administrativos_depto(string $depto = 'Departamento de SOLOS'): array
{
  $depto = trim($depto);

  if (!is_tecnico_administrativo_depto_valido($depto)) {
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
      'message' => 'Não foi possível carregar os técnicos administrativos.',
    ];
  }

  $sql = $db->prepare(
    "SELECT 
      NomeUsu,
      TitulaUsu,
      EmailUsu,
      FoneUsu,
      chavePrimaria
    FROM programa 
    WHERE VinculoUsu = %s
      AND DEPTO = %s
    ORDER BY NomeUsu ASC",
    'Funcionário',
    $depto
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log('Erro ao buscar técnicos administrativos do departamento ' . $depto . ': ' . $db->last_error);

    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os técnicos administrativos.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'error' => false,
    'message' => '',
  ];
}
