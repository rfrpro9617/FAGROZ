<?php

require_once get_theme_file_path('/inc/queries/repo-concluintes-curso.php');

function render_concluintes_curso_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'curso' => 'AGRONOMIA',
    ],
    $atts,
    'concluintes_curso'
  );

  $curso = sanitize_text_field((string) $atts['curso']);
  $resultado = get_concluintes_curso($curso);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  $agrupado = [];

  foreach ($resultado['items'] as $item) {
    $periodo = trim((string) ($item->concluiuCurso ?? ''));
    $nome = trim((string) ($item->NomeUsu ?? ''));

    if ($periodo === '' || $nome === '') {
      continue;
    }

    $agrupado[$periodo][] = $nome;
  }

  $filter_id = wp_unique_id('concluintes-curso-filtro-');

  ob_start();
?>
  <div class="concluintes-curso" data-concluintes-curso>
    <p><strong>TOTAL: <?= esc_html(count($resultado['items'])); ?></strong></p>

    <?php if (empty($agrupado)) : ?>
      <p>Nenhum concluinte encontrado.</p>
    <?php else : ?>
      <div class="alunos-em-curso__filters-area">
        <div class="alunos-em-curso__filter-row">
          <div class="alunos-em-curso__filter">
            <label for="<?= esc_attr($filter_id); ?>-nome">
              <span>Nome</span>
              <input
                id="<?= esc_attr($filter_id); ?>-nome"
                type="search"
                data-concluintes-curso-nome
                placeholder="Digite o nome do concluinte"
                autocomplete="off"
              >
            </label>
          </div>
          <div class="alunos-em-curso__filter">
            <label for="<?= esc_attr($filter_id); ?>-periodo">
              <span>Data/período de conclusão</span>
              <input
                id="<?= esc_attr($filter_id); ?>-periodo"
                type="search"
                data-concluintes-curso-periodo
                placeholder="Digite o ano ou período (ex.: 2025-II)"
                autocomplete="off"
              >
            </label>
          </div>
        </div>
      </div>

      <?php foreach ($agrupado as $periodo => $nomes) : ?>
        <section data-concluintes-curso-grupo>
          <h3><?= esc_html($periodo); ?></h3>
          <?php foreach ($nomes as $nome) : ?>
            <div
              data-concluintes-curso-item
              data-periodo="<?= esc_attr($periodo); ?>"
            ><?= esc_html($nome); ?></div>
          <?php endforeach; ?>
        </section>
      <?php endforeach; ?>
      <p data-concluintes-curso-empty hidden>Nenhum concluinte corresponde aos filtros.</p>
    <?php endif; ?>
  </div>
<?php

  return ob_get_clean();
}

add_shortcode('concluintes_curso', 'render_concluintes_curso_shortcode');
