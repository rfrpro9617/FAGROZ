<?php get_header(); ?>
<?php
$acfHeroImage = get_field('hero_image');
$title = $acfHeroImage['title'] ?: get_the_title();
$bg_photo = $acfHeroImage['bg_photo'] ?? '';
if (is_array($bg_photo)) {
  $bg_photo = $bg_photo['url'] ?? '';
}
if (empty($bg_photo)) {
  $bg_photo = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
get_template_part(
  'template-parts/components/hero/post-hero-image',
  null,
  [
    'title' => $title,
    'image' => $bg_photo,
    'text_position' => 'center',
  ]
);
$search_query = isset($_GET['docentes_search']) ? sanitize_text_field(wp_unslash($_GET['docentes_search'])) : '';
$requested_page = isset($_GET['paged'])
  ? absint($_GET['paged'])
  : (isset($_GET['page']) ? absint($_GET['page']) : 0);
$current_query_page = max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: 0));
$paged = max(1, $requested_page ?: $current_query_page);
$docentes_data = function_exists('get_professores')
  ? get_professores([
    'search' => $search_query,
    'paged' => $paged,
  ])
  : [
    'items' => [],
    'total_items' => 0,
    'total_pages' => 1,
    'paged' => $paged,
    'search' => $search_query,
  ];
$professores = $docentes_data['items'] ?? [];
$total_pages = max(1, (int) ($docentes_data['total_pages'] ?? 1));
$current_page = max(1, (int) ($docentes_data['paged'] ?? $paged));
?>
<section class="page-docentes">
  <div class="container">
    <form class="page-docentes__filters" method="get" role="search">
      <input type="hidden" name="paged" value="1">
      <input
        type="search"
        class="page-docentes__search"
        id="docentes-search"
        name="docentes_search"
        placeholder="Pesquisar..."
        value="<?php echo esc_attr($search_query); ?>">
      <button type="submit" class="button button--secondary">Buscar</button>
    </form>
    <div class="page-docentes__list">
      <div class="page-docentes__empty" id="docentes-empty" style="<?php echo !empty($professores) ? 'display: none;' : 'display: block;'; ?>">
        <?php echo esc_html($search_query ? 'Nenhum docente encontrado para sua busca.' : 'Nenhum docente encontrado.'); ?>
      </div>
      <?php if (!empty($professores)) : ?>
        <?php foreach ($professores as $docente) : ?>
          <article class="page-docentes__item">
            <div class="page-docentes__name">
              <?= esc_html($docente->NomeUsu); ?>
            </div>
            <div class="page-docentes__depto">
              <?= esc_html($docente->depto); ?>
            </div>
            <div class="page-docentes__email">
              <a href="mailto:<?= esc_attr($docente->EmailUsu); ?>">
                <?= esc_html($docente->EmailUsu); ?>
              </a>
            </div>
            <div class="page-docentes__lattes">
              <?php if (!empty($docente->lattes)) : ?>
                <a
                  href="<?= esc_url('https://lattes.cnpq.br/' . $docente->lattes); ?>"
                  target="_blank"
                  rel="noopener noreferrer">
                  Ir para o Lattes
                </a>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <?php if ($total_pages > 1) : ?>
      <?php
      $pagination_base = get_pagenum_link(1);

      if ($search_query !== '') {
        $pagination_base = add_query_arg('docentes_search', $search_query, $pagination_base);
      }

      $pagination_base = add_query_arg('paged', '%#%', $pagination_base);

      $pagination_links = paginate_links([
        'base' => $pagination_base,
        'format' => '',
        'current' => $current_page,
        'total' => $total_pages,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'type' => 'list',
      ]);
      ?>
      <?php if (!empty($pagination_links)) : ?>
        <nav class="page-docentes__pagination" aria-label="Paginação de docentes">
          <?php echo wp_kses_post($pagination_links); ?>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php get_footer(); ?>