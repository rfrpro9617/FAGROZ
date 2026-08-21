<?php
function get_professores(array $args = []): array
{
  try {
    $db = get_programa_db();

    if (!$db instanceof wpdb) {
      return [
        'items' => [],
        'total_items' => 0,
        'total_pages' => 1,
        'paged' => max(1, $args['paged'] ?? (isset($_GET['paged']) ? absint($_GET['paged']) : 1)),
        'search' => $args['search'] ?? (isset($_GET['docentes_search']) ? sanitize_text_field(wp_unslash($_GET['docentes_search'])) : ''),
      ];
    }

    $search_term = $args['search'] ?? (
      isset($_GET['docentes_search'])
        ? sanitize_text_field(wp_unslash($_GET['docentes_search']))
        : ''
    );

    $page = max(
      1,
      $args['paged'] ?? (isset($_GET['paged']) ? absint($_GET['paged']) : 1)
    );

    $per_page = 10;

    $where = "WHERE VinculoUsu = %s AND DEPTO <> %s";
    $params = ['Professor', 'OUTRO DEPARTAMENTO'];

    if ($search_term !== '') {
      $where .= " AND (NomeUsu LIKE %s OR EmailUsu LIKE %s OR lattes LIKE %s OR  depto LIKE %s)";

      $like = '%' . $db->esc_like($search_term) . '%';

      $params[] = $like;
      $params[] = $like;
      $params[] = $like;
      $params[] = $like;
    }

    // Total de registros
    $count_sql = $db->prepare(
      "SELECT COUNT(*) AS total
       FROM programa
       $where",
      ...$params
    );

    $count_row = $db->get_row($count_sql);

    $total_items = (int) ($count_row->total ?? 0);
    $total_pages = max(1, (int) ceil($total_items / $per_page));

    // Garante que a página não ultrapasse o total
    $page = min($page, $total_pages);

    $offset = ($page - 1) * $per_page;

    $prepare_args = array_merge($params, [$per_page, $offset]);

    // Incluído DEPTO no retorno
    $sql = $db->prepare(
      "SELECT
         NomeUsu,
         EmailUsu,
         lattes,
         DEPTO AS depto
       FROM programa
       $where
       ORDER BY NomeUsu ASC
       LIMIT %d OFFSET %d",
      ...$prepare_args
    );

    $results = $db->get_results($sql);

    if (!empty($db->last_error)) {
      error_log('Erro ao buscar professores: ' . $db->last_error);

      return [
        'items' => [],
        'total_items' => 0,
        'total_pages' => 1,
        'paged' => $page,
        'search' => $search_term,
      ];
    }

    return [
      'items' => $results ?? [],
      'total_items' => $total_items,
      'total_pages' => $total_pages,
      'paged' => $page,
      'search' => $search_term,
    ];

  } catch (Exception $e) {
    error_log('Exceção em get_professores: ' . $e->getMessage());

    return [
      'items' => [],
      'total_items' => 0,
      'total_pages' => 1,
      'paged' => 1,
      'search' => '',
    ];
  }
}


function get_servidores(array $args = []): array
{
  try {
    $db = get_programa_db();

    if (!$db instanceof wpdb) {
      return [
        'items' => [],
        'total_items' => 0,
        'total_pages' => 1,
        'paged' => max(1, $args['paged'] ?? (isset($_GET['paged']) ? absint($_GET['paged']) : 1)),
        'search' => $args['search'] ?? (isset($_GET['servidores_search']) ? sanitize_text_field(wp_unslash($_GET['servidores_search'])) : ''),
      ];
    }

    $search_term = $args['search'] ?? (
      isset($_GET['servidores_search'])
        ? sanitize_text_field(wp_unslash($_GET['servidores_search']))
        : ''
    );

    $page = max(
      1,
      $args['paged'] ?? (isset($_GET['paged']) ? absint($_GET['paged']) : 1)
    );

    $per_page = 10;

    $where = "WHERE VinculoUsu = %s";
    $params = ['Funcionário'];

    if ($search_term !== '') {
      $where .= " AND (NomeUsu LIKE %s OR EmailUsu LIKE %s OR lattes LIKE %s OR  depto LIKE %s)";

      $like = '%' . $db->esc_like($search_term) . '%';

      $params[] = $like;
      $params[] = $like;
      $params[] = $like;
      $params[] = $like;
    }

    // Total de registros
    $count_sql = $db->prepare(
      "SELECT COUNT(*) AS total
       FROM programa
       $where",
      ...$params
    );

    $count_row = $db->get_row($count_sql);

    $total_items = (int) ($count_row->total ?? 0);
    $total_pages = max(1, (int) ceil($total_items / $per_page));

    // Garante que a página não ultrapasse o total
    $page = min($page, $total_pages);

    $offset = ($page - 1) * $per_page;

    $prepare_args = array_merge($params, [$per_page, $offset]);

    // Incluído DEPTO no retorno
    $sql = $db->prepare(
      "SELECT
         NomeUsu,
         EmailUsu,
         lattes,
         DEPTO AS depto
       FROM programa
       $where
       ORDER BY NomeUsu ASC
       LIMIT %d OFFSET %d",
      ...$prepare_args
    );

    $results = $db->get_results($sql);

    if (!empty($db->last_error)) {
      error_log('Erro ao buscar funcionários: ' . $db->last_error);

      return [
        'items' => [],
        'total_items' => 0,
        'total_pages' => 1,
        'paged' => $page,
        'search' => $search_term,
      ];
    }

    return [
      'items' => $results ?? [],
      'total_items' => $total_items,
      'total_pages' => $total_pages,
      'paged' => $page,
      'search' => $search_term,
    ];

  } catch (Exception $e) {
    error_log('Exceção em get_servidores: ' . $e->getMessage());

    return [
      'items' => [],
      'total_items' => 0,
      'total_pages' => 1,
      'paged' => 1,
      'search' => '',
    ];
  }
}