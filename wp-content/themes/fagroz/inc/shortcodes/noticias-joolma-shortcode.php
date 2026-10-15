<?php
require_once get_theme_file_path('/inc/queries/repo-noticias-joolma.php');

function render_content_list(array $noticias, string $mostra): string
{
  if (empty($noticias)) {
    return '<p>Nenhum conteúdo encontrado.</p>';
  }

  $noticias_por_data = [];

  foreach ($noticias as $content) {
    $data = preg_replace(
      '/[^0-9]/',
      '',
      (string) ($content->data ?? '')
    );

    if (strlen($data) !== 8) {
      continue;
    }

    $noticias_por_data[$data][] = $content;
  }

  if (empty($noticias_por_data)) {
    return '<p>Nenhuma notícia encontrada.</p>';
  }

  ob_start();
?>
  <section
    class="dados-joolma-shortcode"
    aria-labelledby="dados-joolma-shortcode-title">
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
          <?php foreach ($itens as $content) : ?>
            <?php
            $id = absint($content->id ?? 0);
            $titulo = trim(
              wp_strip_all_tags(
                (string) ($content->titulo ?? '')
              )
            );
            if ($id <= 0 || $titulo === '') {
              continue;
            }
            $url = add_query_arg(
              'content',
              $id,
              get_permalink()
            );
            ?>
            <li class="dados-joolma__item">
              <a
                class="dados-joolma__link"
                href="<?= esc_url($url); ?>"
                target="_blank">
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


function render_content_individual(
  array $noticias,
  int $content_id
): string {

  if ($content_id <= 0) {
    return '';
  }

  $conteudo_encontrado = null;

  foreach ($noticias as $content) {
    $id = absint($content->id ?? 0);

    if ($id === $content_id) {
      $conteudo_encontrado = $content;
      break;
    }
  }

  if (!$conteudo_encontrado) {
    return '<p>Conteúdo não encontrado.</p>';
  }

  $titulo = trim(
    wp_strip_all_tags(
      (string) ($conteudo_encontrado->titulo ?? '')
    )
  );

  $texto = (string) (
    $conteudo_encontrado->texto ?? ''
  );

  $data = preg_replace(
    '/[^0-9]/',
    '',
    (string) ($conteudo_encontrado->data ?? '')
  );

  $data_formatada = '';

  if (strlen($data) === 8) {
    $data_formatada =
      substr($data, 6, 2) . '/' .
      substr($data, 4, 2) . '/' .
      substr($data, 0, 4);
  }

  $url_voltar = remove_query_arg(
    'content',
    get_permalink()
  );

  ob_start();
?>
  <article class="content-individual">
    <div class="content-individual__content">
      <?php if ($texto !== '') : ?>
        <?= $texto; ?>
      <?php else : ?>
        <p>Conteúdo da notícia não disponível.</p>
      <?php endif; ?>
    </div>
    <footer class="content-individual__footer">
      <a
        class="content-individual__back"
        href="<?= esc_url($url_voltar); ?>">
        Voltar
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

  $resultado = get_editor_content($mostra);

  if (!empty($resultado['error'])) {
    return '<p>' .
      esc_html($resultado['message']) .
      '</p>';
  }

  $noticias = $resultado['items'];

  $content_id = absint(
    $_GET['content'] ?? 0
  );

  if ($content_id > 0) {
    return render_content_individual(
      $noticias,
      $content_id
    );
  }

  return render_content_list(
    $noticias,
    $mostra
  );
}

add_shortcode(
  'noticias',
  'render_noticias_shortcode'
);
