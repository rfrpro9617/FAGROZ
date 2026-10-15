<?php

require_once get_theme_file_path('/inc/queries/repo-noticias-joolma.php');


function render_noticias_list(array $noticias, string $mostra): string
{
  if (empty($noticias)) {
    return '<p>Nenhuma notícia encontrada.</p>';
  }

  $noticias_por_data = [];

  foreach ($noticias as $noticia) {
    $data = preg_replace(
      '/[^0-9]/',
      '',
      (string) ($noticia->data ?? '')
    );

    if (strlen($data) !== 8) {
      continue;
    }

    $noticias_por_data[$data][] = $noticia;
  }

  if (empty($noticias_por_data)) {
    return '<p>Nenhuma notícia encontrada.</p>';
  }

  ob_start();
?>

  <section
    class="dados-joolma-shortcode"
    aria-labelledby="dados-joolma-shortcode-title">

    <nav
      class="dados-joolma__actions"
      aria-label="Ações das notícias">
      <a
        href="#"
        onclick="window.print(); return false;">
        Imprimir
      </a>

      <span aria-hidden="true">|</span>

      <a
        href="mailto:?subject=Notícias&body=<?= esc_attr(esc_url(get_permalink())); ?>">
        Email
      </a>
    </nav>


    <?php foreach ($noticias_por_data as $data => $itens) : ?>

      <div class="dados-joolma__group">

        <h3 class="dados-joolma__date">
          <?= esc_html(
            substr($data, 6, 2) . '/' .
              substr($data, 4, 2) . '/' .
              substr($data, 0, 4)
          ); ?>
        </h3>


        <ul class="dados-joolma__list">

          <?php foreach ($itens as $noticia) : ?>

            <?php
            $id = absint($noticia->id ?? 0);

            $titulo = trim(
              wp_strip_all_tags(
                (string) ($noticia->titulo ?? '')
              )
            );

            if ($id <= 0 || $titulo === '') {
              continue;
            }

            $url = add_query_arg(
              'noticia',
              $id,
              get_permalink()
            );
            ?>

            <li class="dados-joolma__item">

              <a
                class="dados-joolma__link"
                href="<?= esc_url($url); ?>">
                <?= esc_html($titulo); ?>
              </a>

            </li>

          <?php endforeach; ?>

        </ul>

      </div>

    <?php endforeach; ?>

  </section>

<?php

  return ob_get_clean();
}


function render_noticia_individual(
  array $noticias,
  int $noticia_id
): string {

  if ($noticia_id <= 0) {
    return '';
  }

  $noticia_encontrada = null;

  foreach ($noticias as $noticia) {

    $id = absint($noticia->id ?? 0);

    if ($id === $noticia_id) {
      $noticia_encontrada = $noticia;
      break;
    }
  }

  if (!$noticia_encontrada) {
    return '<p>Notícia não encontrada.</p>';
  }

  $titulo = trim(
    wp_strip_all_tags(
      (string) ($noticia_encontrada->titulo ?? '')
    )
  );

  $texto = (string) (
    $noticia_encontrada->texto ?? ''
  );

  $data = preg_replace(
    '/[^0-9]/',
    '',
    (string) ($noticia_encontrada->data ?? '')
  );

  $data_formatada = '';

  if (strlen($data) === 8) {
    $data_formatada =
      substr($data, 6, 2) . '/' .
      substr($data, 4, 2) . '/' .
      substr($data, 0, 4);
  }

  $url_voltar = remove_query_arg(
    'noticia',
    get_permalink()
  );

  ob_start();
?>

  <article class="noticia-individual">

    <nav
      class="noticia-individual__actions"
      aria-label="Ações da notícia">

      <a
        class="noticia-individual__back"
        href="<?= esc_url($url_voltar); ?>">
        ← Voltar para notícias
      </a>

      <span aria-hidden="true">|</span>

      <a
        href="#"
        onclick="window.print(); return false;">
        Imprimir
      </a>

      <span aria-hidden="true">|</span>

      <a
        href="mailto:?subject=<?= esc_attr($titulo); ?>&body=<?= esc_attr(esc_url(get_permalink())); ?>">
        Email
      </a>

    </nav>


    <header class="noticia-individual__header">

      <h1 class="noticia-individual__title">
        <?= esc_html($titulo); ?>
      </h1>

      <?php if ($data_formatada !== '') : ?>

        <div class="noticia-individual__date">
          <?= esc_html($data_formatada); ?>
        </div>

      <?php endif; ?>

    </header>


    <div class="noticia-individual__content">

      <?php if ($texto !== '') : ?>

        <?= wp_kses_post($texto); ?>

      <?php else : ?>

        <p>Conteúdo da notícia não disponível.</p>

      <?php endif; ?>

    </div>


    <footer class="noticia-individual__footer">

      <a
        class="noticia-individual__back"
        href="<?= esc_url($url_voltar); ?>">
        ← Voltar para notícias
      </a>

    </footer>

  </article>

<?php

  return ob_get_clean();
}


function render_noticias_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'mostra' => 'agronomia',
      'vis' => '',
    ],
    $atts,
    'noticias'
  );

  $mostra = trim(
    (string) (
      $atts['vis'] !== ''
      ? $atts['vis']
      : $atts['mostra']
    )
  );

  if (!is_editor_mostra_valida($mostra)) {
    return '<p>Categoria de notícias inválida.</p>';
  }

  /*
   * UMA ÚNICA CONSULTA.
   *
   * Já traz id, título, data, mostra e texto.
   */
  $resultado = get_editor_noticias($mostra);

  if (!empty($resultado['error'])) {
    return '<p>' .
      esc_html($resultado['message']) .
      '</p>';
  }

  $noticias = $resultado['items'];

  /*
   * Verifica se o usuário está
   * visualizando uma notícia específica.
   */
  $noticia_id = absint(
    $_GET['noticia'] ?? 0
  );

  /*
   * Se tiver ?noticia=123,
   * mostra somente essa notícia.
   */
  if ($noticia_id > 0) {

    return render_noticia_individual(
      $noticias,
      $noticia_id
    );
  }

  /*
   * Caso contrário, mostra a lista.
   */
  return render_noticias_list(
    $noticias,
    $mostra
  );
}


add_shortcode(
  'noticias',
  'render_noticias_shortcode'
);
