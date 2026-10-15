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

/**
 * Obtém o e-mail somente para um docente já identificado pelo nome.
 *
 * Isto reduz a coleta casual no HTML, mas não torna o e-mail secreto:
 * depois que o servidor o entrega ao navegador, ele pode ser capturado.
 */
function get_professor_email_by_nome(string $nome): ?string
{
  try {
    $db = get_programa_db();

    if (!$db instanceof wpdb || $nome === '') {
      return null;
    }

    $sql = $db->prepare(
      "SELECT EmailUsu
       FROM programa
       WHERE VinculoUsu = %s
         AND DEPTO <> %s
         AND NomeUsu = %s
       LIMIT 2",
      'Professor',
      'OUTRO DEPARTAMENTO',
      $nome
    );

    $rows = $db->get_results($sql);

    if (!empty($db->last_error) || count($rows) !== 1) {
      return null;
    }

    $email = $rows[0]->EmailUsu ?? '';

    return is_string($email) && is_email($email) ? $email : null;
  } catch (Exception $e) {
    error_log('Exceção em get_professor_email_by_nome: ' . $e->getMessage());
    return null;
  }
}

function fagroz_ajax_get_docente_email(): void
{
  check_ajax_referer('fagroz_docente_email', 'nonce');

  $nome = isset($_POST['nome'])
    ? sanitize_text_field(wp_unslash($_POST['nome']))
    : '';

  if ($nome === '') {
    wp_send_json_error(['message' => 'Identificador de docente inválido.'], 400);
  }

  $email = get_professor_email_by_nome($nome);

  if ($email === null) {
    wp_send_json_error(['message' => 'Não foi possível localizar o e-mail deste docente.'], 404);
  }

  wp_send_json_success(['email' => $email]);
}

function fagroz_ajax_get_servidor_email(): void
{
  check_ajax_referer('fagroz_docente_email', 'nonce');

  $nome = isset($_POST['nome'])
    ? sanitize_text_field(wp_unslash($_POST['nome']))
    : '';

  if ($nome === '') {
    wp_send_json_error(['message' => 'Identificador de servidor inválido.'], 400);
  }

  try {
    $db = get_programa_db();

    if (!$db instanceof wpdb) {
      wp_send_json_error(['message' => 'Não foi possível consultar o servidor.'], 500);
    }

    $sql = $db->prepare(
      "SELECT EmailUsu
       FROM programa
       WHERE VinculoUsu = %s
         AND NomeUsu = %s
       LIMIT 2",
      'Funcionário',
      $nome
    );

    $rows = $db->get_results($sql);

    if (!empty($db->last_error) || count($rows) !== 1) {
      wp_send_json_error(['message' => 'Não foi possível localizar o e-mail deste servidor.'], 404);
    }

    $email = $rows[0]->EmailUsu ?? '';

    if (!is_string($email) || !is_email($email)) {
      wp_send_json_error(['message' => 'O e-mail deste servidor está indisponível.'], 404);
    }

    wp_send_json_success(['email' => $email]);
  } catch (Exception $e) {
    error_log('Exceção em fagroz_ajax_get_servidor_email: ' . $e->getMessage());
    wp_send_json_error(['message' => 'Não foi possível consultar o servidor.'], 500);
  }
}

add_action('wp_ajax_fagroz_get_docente_email', 'fagroz_ajax_get_docente_email');
add_action('wp_ajax_nopriv_fagroz_get_docente_email', 'fagroz_ajax_get_docente_email');
add_action('wp_ajax_fagroz_get_servidor_email', 'fagroz_ajax_get_servidor_email');
add_action('wp_ajax_nopriv_fagroz_get_servidor_email', 'fagroz_ajax_get_servidor_email');
