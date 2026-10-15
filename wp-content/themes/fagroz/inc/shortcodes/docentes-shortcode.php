<?php

require_once get_theme_file_path('/inc/queries/repo-docentes-ppg.php');

function render_docentes_ppg_list(array $docentes): string
{
  if (empty($docentes)) {
    return '<p>Nenhum docente encontrado.</p>';
  }

  ob_start();
?>
  <div class="docentes-ppg">
    <p class="docentes-ppg__total">
      <strong>
        TOTAL:
        <span><?= esc_html(count($docentes)); ?></span>
      </strong>
    </p>

    <div class="docentes-ppg__grid">
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
        <article class="docentes-ppg__card">
          <?php if ($foto_url !== '') : ?>
            <div class="docentes-ppg__photo">
              <img
                src="<?= esc_url($foto_url); ?>"
                alt="Foto de <?= esc_attr($nome); ?>"
                loading="lazy">
            </div>
          <?php endif; ?>

          <div class="docentes-ppg__content">
            <?php if ($nome !== '') : ?>
              <h3 class="docentes-ppg__name"><?= esc_html($nome); ?></h3>
            <?php endif; ?>

            <?php if ($titulacao !== '') : ?>
              <p class="docentes-ppg__title"><?= $titulacao; ?></p>
            <?php endif; ?>

            <div class="docentes-ppg__details">
              <?php if ($email !== '') : ?>
                <a class="docentes-ppg__detail" href="mailto:<?= esc_attr($email); ?>">
                  <?= esc_html($email); ?>
                </a>
              <?php endif; ?>

              <?php if ($telefone !== '') : ?>
                <a class="docentes-ppg__detail" href="tel:<?= esc_attr($telefone_url); ?>">
                  <?= esc_html($telefone); ?>
                </a>
              <?php endif; ?>
            </div>

            <?php if ($lattes_url !== '') : ?>
              <a
                class="docentes-ppg__lattes"
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
    'docentes'
  );

  $ppg = (string) $atts['ppg'];

  if (!is_docente_ppg_valido($ppg)) {
    return '<p>Programa de pós-graduação inválido.</p>';
  }

  $resultado = get_docentes_ppg($ppg);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  return render_docentes_ppg_list($resultado['items']);
}

add_shortcode('docentes', 'render_docentes_ppg_shortcode');
