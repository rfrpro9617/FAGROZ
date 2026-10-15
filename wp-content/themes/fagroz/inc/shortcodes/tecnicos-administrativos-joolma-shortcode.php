<?php

require_once get_theme_file_path('/inc/queries/repo-tecnico-administrativo-joolma.php');

function render_tecnicos_administrativos_list(array $docentes): string
{
  if (empty($docentes)) {
    return '<p>Nenhum docente encontrado.</p>';
  }

  ob_start();
?>
  <div class="docentes">
    <p class="docentes__total">
      <strong>
        TOTAL:
        <span><?= esc_html(count($docentes)); ?></span>
      </strong>
    </p>

    <div class="docentes__grid">
      <?php foreach ($docentes as $docente) : ?>
        <?php
        $nome = trim((string) ($docente->NomeUsu ?? ''));
        $titulacao = trim((string) ($docente->TitulaUsu ?? ''));
        $email = trim((string) ($docente->EmailUsu ?? ''));
        $telefone = trim((string) ($docente->FoneUsu ?? ''));
        $foto_id = absint($docente->chavePrimaria ?? 0);
        $foto_url = $foto_id > 0
          ? 'https://www.ufrgs.br/fagro/materiais/fotos/' . $foto_id . '.jpg'
          : '';
        $telefone_url = preg_replace('/[^0-9+]/', '', $telefone);
        ?>
        <article class="docentes__card">
          <?php if ($foto_url !== '') : ?>
            <div class="docentes__photo">
              <img
                src="<?= esc_url($foto_url); ?>"
                alt="Foto de <?= esc_attr($nome); ?>"
                loading="lazy">
            </div>
          <?php endif; ?>

          <div class="docentes__content">
            <?php if ($nome !== '') : ?>
              <h3 class="docentes__name"><?= esc_html($nome); ?></h3>
            <?php endif; ?>

            <?php if ($titulacao !== '') : ?>
              <p class="docentes__title"><?= $titulacao; ?></p>
            <?php endif; ?>

            <div class="docentes__details">
              <?php if ($email !== '') : ?>
                <a class="docentes__detail" href="mailto:<?= esc_attr($email); ?>">
                  <?= esc_html($email); ?>
                </a>
              <?php endif; ?>

              <?php if ($telefone !== '') : ?>
                <a class="docentes__detail" href="tel:<?= esc_attr($telefone_url); ?>">
                  <?= esc_html($telefone); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
<?php

  return ob_get_clean();
}

function render_tecnicos_administrativos_depto_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'depto' => 'Departamento de SOLOS',
    ],
    $atts,
    'tecnicos_administrativos_depto'
  );

  $depto = (string) $atts['depto'];

  if (!is_tecnico_administrativo_depto_valido($depto)) {
    return '<p>Departamento inválido.</p>';
  }

  $resultado = get_tecnicos_administrativos_depto($depto);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  return render_tecnicos_administrativos_list($resultado['items']);
}

add_shortcode('tecnicos_administrativos_depto', 'render_tecnicos_administrativos_depto_shortcode');
