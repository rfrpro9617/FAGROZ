<!--
  Exibe qualquer página estática que não tenha um template especifico
  Ordem para chegar nesse arquivo:
  page-{slug}.php
  *page.php
  singular.php
  index.php
-->
<?php
get_header();
if (have_posts()) :
  while (have_posts()) :
    the_post();

    /**
     * Processamos o conteúdo antes de exibi-lo para:
     *
     * 1. Encontrar todos os headings (H1 até H6);
     * 2. Verificar se possuem um ID definido no Gutenberg;
     * 3. Adicionar à navegação lateral somente os headings
     *    que já possuem ID;
     * 4. Não gerar ou alterar IDs automaticamente.
     */

    $content = apply_filters('the_content', get_the_content());
    $navigation_items = [];

    $content = preg_replace_callback(
      '/<h([1-6])\b([^>]*)>(.*?)<\/h\1>/is',
      function ($matches) use (&$navigation_items) {
        $attributes = $matches[2];
        $title = wp_strip_all_tags($matches[3]);

        if (
          preg_match(
            '/\bid=(["\'])(.*?)\1/i',
            $attributes,
            $id_match
          )
          && !empty($id_match[2])
          && !empty($title)
        ) {
          $navigation_items[] = [
            'id' => $id_match[2],
            'title' => $title,
          ];
        }

        return $matches[0];
      },
      $content
    );
?>
    <div class="container container--single container--single-cpt page-section">
      <div class="single-article single-article--editorial">
        <?php if (!empty($navigation_items)) : ?>
          <aside class="single-article__sidebar">
            <nav class="single-article__nav" aria-label="Nesta Página">
              <ul>
                <?php foreach ($navigation_items as $item) : ?>
                  <li>
                    <a href="#<?= esc_attr($item['id']); ?>">
                      <?= esc_html($item['title']); ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </nav>
          </aside>
        <?php endif; ?>
        <main class="single-article__main">
          <article id="post-<?php the_ID(); ?>" <?php post_class('single-article__entry'); ?>>
            <header class="single-article__header">
              <h1 class="single-article__title">
                <?php the_title(); ?>
              </h1>
            </header>
            <div class="single-article__content editorial-content">
              <?php the_content(); ?>
            </div>
          </article>
        </main>
      </div>
    </div>
<?php
  endwhile;
endif;
get_footer();
