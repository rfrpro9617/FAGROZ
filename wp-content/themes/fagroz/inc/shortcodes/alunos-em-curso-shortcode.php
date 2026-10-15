<?php

require_once get_theme_file_path('/inc/queries/repo-alunos-em-curso.php');

function render_alunos_em_curso_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'curso' => 'AGRONOMIA',
    ],
    $atts,
    'alunos_em_curso'
  );

  $curso = sanitize_text_field((string) $atts['curso']);
  $resultado = get_alunos_em_curso($curso);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  $filter_id = wp_unique_id('alunos-em-curso-filtro-');

  ob_start();
?>
  <div class="alunos-em-curso" data-alunos-em-curso>
    <p><strong>TOTAL: <?= esc_html($resultado['total']); ?></strong></p>

    <?php if (empty($resultado['items'])) : ?>
      <p>Nenhum aluno em curso encontrado.</p>
    <?php else : ?>
      <div class="alunos-em-curso__filters-area">
        <div class="alunos-em-curso__filter-row">
          <div class="alunos-em-curso__filter">
            <label for="<?= esc_attr($filter_id); ?>">
              <span>Nome</span>
              <input
                id="<?= esc_attr($filter_id); ?>"
                type="search"
                data-alunos-em-curso-filter
                placeholder="Digite o nome do aluno"
                autocomplete="off"
              >
            </label>
          </div>
        </div>
      </div>
      <div class="alunos-em-curso__list">
        <?php foreach ($resultado['items'] as $aluno) : ?>
          <?php $nome = trim((string) ($aluno->NomeUsu ?? '')); ?>
          <?php if ($nome !== '') : ?>
            <div class="alunos-em-curso__name" data-alunos-em-curso-name><?= esc_html($nome); ?></div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <p class="alunos-em-curso__empty" data-alunos-em-curso-empty hidden>Nenhum aluno corresponde à busca.</p>
    <?php endif; ?>
  </div>
<?php

  return ob_get_clean();
}

add_shortcode('alunos_em_curso', 'render_alunos_em_curso_shortcode');
