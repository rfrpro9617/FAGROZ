<?php

require_once get_theme_file_path('/inc/queries/repo-post.php');

function render_noticias_por_tag_shortcode($atts = []): string
{
  if (
    absint($_GET['noticia'] ?? 0) > 0 ||
    absint($_GET['content'] ?? 0) > 0
  ) {
    return '';
  }

  $atts = shortcode_atts(
    [
      'tag' => '',
    ],
    $atts,
    'noticias_por_tag'
  );

  $tag = sanitize_key((string) $atts['tag']);

  if ($tag === '') {
    return '<p>Informe uma tag válida.</p>';
  }

  $resultado = fagroz_get_home_posts_data([
    'tag' => $tag,
  ]);
  $noticias = $resultado['posts'] ?? [];

  if (empty($noticias)) {
    return '<p>Nenhuma notícia encontrada.</p>';
  }

  $noticias_por_data = [];

  foreach ($noticias as $noticia) {
    $data = trim((string) ($noticia['date'] ?? ''));

    if ($data !== '') {
      $noticias_por_data[$data][] = $noticia;
    }
  }

  if (empty($noticias_por_data)) {
    return '<p>Nenhuma notícia encontrada.</p>';
  }

  ob_start();
?>
  <section
    class="dados-joolma-shortcode"
    aria-label="Notícias por tag">
    <h2>FAGROZ</h2>
    <?php foreach ($noticias_por_data as $data => $itens) : ?>
      <div class="dados-joolma__group">
        <h3 class="dados-joolma__date">
          <?= esc_html($data); ?>
        </h3>
        <ul class="dados-joolma__list">
          <?php foreach ($itens as $noticia) : ?>
            <?php
            $titulo = trim(wp_strip_all_tags((string) ($noticia['title'] ?? '')));
            $url = (string) ($noticia['permalink'] ?? '');

            if ($titulo === '' || $url === '') {
              continue;
            }
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

    <?php if (!empty($resultado['pagination'])) : ?>
      <nav class="home-posts__pagination" aria-label="Paginação das notícias">
        <?= wp_kses_post($resultado['pagination']); ?>
      </nav>
    <?php endif; ?>
    <hr>
    <h2>FAGRO</h2>
  </section>
<?php

  return ob_get_clean();
}

add_shortcode(
  'noticias_por_tag',
  'render_noticias_por_tag_shortcode'
);
