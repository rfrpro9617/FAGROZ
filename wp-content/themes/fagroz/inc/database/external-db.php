<?php
function get_programa_db()
{
  static $db = null;
  if ($db === null) {
    if (!class_exists('wpdb')) {
      error_log('wpdb não está disponível para a conexão externa.');
      return null;
    }

    try {
      $db = new wpdb(
        'agronomia2',
        'Hlm9S6JONqgw',
        'agronomia2',
        'bdlivre.ufrgs.br'
      );

      if (!empty($db->last_error)) {
        error_log('Erro ao conectar ao banco de dados: ' . $db->last_error);
        return null;
      }

      $db->set_charset($db->dbh, 'utf8mb4');
    } catch (Exception $e) {
      error_log('Exceção ao conectar ao banco de dados: ' . $e->getMessage());
      return null;
    }
  }

  return $db;
}
