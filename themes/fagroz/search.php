<?php
get_header();
?>
<div class="container page-section">
  <header class="search-header">
    <h1 class="search-title">
      Resultados
    </h1>
    <p class="search-query">
      <?php echo esc_html(get_search_query()); ?>
    </p>
    <p class="search-count">
      Aproximadamente
      <?php echo esc_html($wp_query->found_posts); ?>
      resultados
    </p>
  </header>
  <?php if (have_posts()) : ?>
    <div class="search-results-list">
      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('search-result'); ?>>
          <h2 class="search-result__title">
            <a href="<?php the_permalink(); ?>" target="_blank">
              <?php the_title(); ?>
            </a>
          </h2>
          <div class="search-result__url">
            <?php echo esc_url(home_url()); ?>
            <?php echo wp_make_link_relative(get_permalink()); ?>
          </div>
          <div class="search-result__excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 35); ?>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <?php
    $total_pages = (int) $wp_query->max_num_pages;
    $current_page = max(1, (int) get_query_var('paged'));

    if ($total_pages > 1) {
      $start_page = max(1, $current_page - 2);
      $end_page = min($total_pages, $current_page + 2);

      if ($end_page - $start_page < 4) {
        if ($start_page === 1) {
          $end_page = min($total_pages, $start_page + 4);
        } else {
          $start_page = max(1, $end_page - 4);
        }
      }

      $items = [];
      $items[] = sprintf(
        '<a href="%s" class="search-pagination__item search-pagination__item--nav %s" data-page="%d" aria-label="Página anterior">&laquo;</a>',
        esc_url(get_pagenum_link(max(1, $current_page - 1))),
        $current_page <= 1 ? 'is-disabled' : '',
        max(1, $current_page - 1),
      );

      for ($page = $start_page; $page <= $end_page; $page++) {
        $is_current = $page === $current_page ? 'current' : '';
        $aria_current = $page === $current_page ? 'aria-current="page"' : '';

        $items[] = sprintf(
          '<a href="%s" class="search-pagination__item %s" data-page="%d" %s aria-label="Ir para a página %d">%d</a>',
          esc_url(get_pagenum_link($page)),
          $is_current,
          $page,
          $aria_current,
          $page,
          $page,
        );
      }

      $items[] = sprintf(
        '<a href="%s" class="search-pagination__item search-pagination__item--nav %s" data-page="%d" aria-label="Próxima página">&raquo;</a>',
        esc_url(get_pagenum_link(min($total_pages, $current_page + 1))),
        $current_page >= $total_pages ? 'is-disabled' : '',
        min($total_pages, $current_page + 1),
      );
    ?>
      <nav class="search-pagination" aria-label="Paginação de resultados da busca">
        <div class="search-pagination__nav">
          <?php echo implode('', $items); ?>
        </div>
      </nav>
    <?php } ?>
  <?php else : ?>
    <p>
      Nenhum resultado encontrado.
    </p>
  <?php endif; ?>
</div>
<?php
get_footer();
?>