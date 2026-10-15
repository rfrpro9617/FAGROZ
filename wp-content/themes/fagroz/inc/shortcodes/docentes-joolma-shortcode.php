<?php

require_once get_theme_file_path('/inc/queries/repo-docentes-joolma.php');

function render_docentes_list(array $docentes): string
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
        $lattes = trim((string) ($docente->lattes ?? ''));
        $foto_id = absint($docente->chavePrimaria ?? 0);
        $foto_url = $foto_id > 0
          ? 'https://www.ufrgs.br/fagro/materiais/fotos/' . $foto_id . '.jpg'
          : '';
        $lattes_url = $lattes;

        if ($lattes !== '' && !preg_match('#^https?://#i', $lattes)) {
          $lattes_url = 'https://lattes.cnpq.br/' . ltrim($lattes, '/');
        }

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

            <?php if ($lattes_url !== '') : ?>
              <a
                class="docentes__lattes"
                href="<?= esc_url($lattes_url); ?>"
                target="_blank"
                rel="noopener noreferrer">
                Currículo Lattes
              </a>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
<?php

  return ob_get_clean();
}

function render_docentes_ppg_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'ppg' => 'Solos',
    ],
    $atts,
    'docentes_ppg'
  );

  $ppg = (string) $atts['ppg'];

  if (!is_docente_ppg_valido($ppg)) {
    return '<p>Programa de pós-graduação inválido.</p>';
  }

  $resultado = get_docentes_ppg($ppg);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  return render_docentes_list($resultado['items']);
}

add_shortcode('docentes_ppg', 'render_docentes_ppg_shortcode');

function render_docentes_depto_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'depto' => 'Departamento de SOLOS',
    ],
    $atts,
    'docentes_depto'
  );

  $depto = (string) $atts['depto'];

  if (!is_docente_depto_valido($depto)) {
    return '<p>Departamento inválido.</p>';
  }

  $resultado = get_docentes_depto($depto);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  return render_docentes_list($resultado['items']);
}

add_shortcode('docentes_depto', 'render_docentes_depto_shortcode');
