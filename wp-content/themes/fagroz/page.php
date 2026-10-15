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
    $title = get_the_title();
    $bg_photo = get_the_post_thumbnail_url(get_the_ID(), 'full');
    get_template_part(
      'template-parts/components/hero/post-hero-image',
      null,
      [
        'title' => $title,
        'image' => $bg_photo,
      ]
    );

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

    if (!empty($navigation_items)) {
      $navigation_panels = [];

      foreach ($navigation_items as $index => &$item) {
        $item['panel_id'] = 'single-tab-panel-' . sanitize_title($item['id']) . '-' . $index;
        $navigation_panels[$item['id']] = $item['panel_id'];
      }
      unset($item);

      $first_item = $navigation_items[0];
      $content = '<section id="' . esc_attr($first_item['panel_id']) . '" class="single-article__tab-panel" role="tabpanel" data-single-tabs-panel aria-labelledby="' . esc_attr($first_item['id']) . '">'
        . preg_replace_callback(
          '/<h([1-6])\b([^>]*)>(.*?)<\/h\1>/is',
          function ($matches) use (&$navigation_panels, $first_item) {
            $attributes = $matches[2];

            if (
              preg_match('/\bid=(["\'])(.*?)\1/i', $attributes, $id_match)
              && isset($navigation_panels[$id_match[2]])
              && $id_match[2] !== $first_item['id']
            ) {
              return '</section><section id="' . esc_attr($navigation_panels[$id_match[2]]) . '" class="single-article__tab-panel" role="tabpanel" data-single-tabs-panel aria-labelledby="' . esc_attr($id_match[2]) . '">' . $matches[0];
            }

            return $matches[0];
          },
          $content
        )
        . '</section>';
    }
?>
    <div class="container container--single container--single-cpt page-section">
      <div class="single-article single-article--editorial">
        <?php if (!empty($navigation_items)) : ?>
          <aside class="single-article__sidebar">
            <nav class="single-article__nav" aria-label="Nesta Página">
              <ul role="tablist" data-single-tabs-nav>
                <?php foreach ($navigation_items as $index => $item) : ?>
                  <li>
                    <button
                      type="button"
                      class="single-article__tab<?= $index === 0 ? ' is-active' : ''; ?>"
                      id="single-tab-<?= esc_attr($index); ?>"
                      role="tab"
                      aria-controls="<?= esc_attr($item['panel_id']); ?>"
                      aria-selected="<?= $index === 0 ? 'true' : 'false'; ?>"
                      tabindex="<?= $index === 0 ? '0' : '-1'; ?>">
                      <?= esc_html($item['title']); ?>
                    </button>
                  </li>
                <?php endforeach; ?>
              </ul>
            </nav>
          </aside>
        <?php endif; ?>
        <main class="single-article__main">
          <article id="post-<?php the_ID(); ?>" <?php post_class('single-article__entry'); ?>>
            <div class="single-article__content editorial-content" data-single-tabs>
              <?= $content; ?>
            </div>
          </article>
        </main>
      </div>
    </div>
<?php
  endwhile;
endif;
get_footer();
