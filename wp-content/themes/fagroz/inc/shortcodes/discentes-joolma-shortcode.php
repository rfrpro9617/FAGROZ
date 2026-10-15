<?php

require_once get_theme_file_path('/inc/queries/repo-discentes-joolma.php');

function render_discentes_ppg_list(array $discentes): string
{
  if (empty($discentes)) {
    return '<p>Nenhum discente encontrado.</p>';
  }

  ob_start();
?>
  <div class="discentes-ppg">
    <p class="discentes-ppg__total">
      <strong>
        TOTAL:
        <span><?= esc_html(count($discentes)); ?></span>
      </strong>
    </p>

    <div class="discentes-ppg__list">
      <?php foreach ($discentes as $discente) : ?>
        <?php
        $nome = trim((string) ($discente->NomeUsu ?? ''));
        $nivel = trim((string) ($discente->NivelAluno ?? ''));
        $orientador = trim((string) ($discente->Orientador ?? ''));
        $nivel = preg_replace('/^PG\s*-\s*/i', '', $nivel);
        ?>
        <article class="discentes-ppg__item">
          <?php if ($nome !== '') : ?>
            <h3 class="discentes-ppg__name"><?= esc_html($nome); ?></h3>
          <?php endif; ?>

          <?php if ($nivel !== '') : ?>
            <div class="discentes-ppg__field">
              <strong>Nível:</strong>
              <?= esc_html($nivel); ?>
            </div>
          <?php endif; ?>

          <?php if ($orientador !== '') : ?>
            <div class="discentes-ppg__field">
              <strong>Orientador:</strong>
              <?= esc_html($orientador); ?>
            </div>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
<?php

  return ob_get_clean();
}

function render_discentes_ppg_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'ppg' => 'Solos',
    ],
    $atts,
    'discentes'
  );

  $ppg = (string) $atts['ppg'];

  if (!is_discente_ppg_valido($ppg)) {
    return '<p>Programa de pós-graduação inválido.</p>';
  }

  $resultado = get_discentes_ppg($ppg);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  return render_discentes_ppg_list($resultado['items']);
}

add_shortcode('discentes', 'render_discentes_ppg_shortcode');
