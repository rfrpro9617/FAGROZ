<?php
$acfNucleos = get_field('our_centers');
$section_title = $acfNucleos['title'] ?? 'Nossos Núcleos';
$search_query = isset($_GET['nucleos_search']) ? sanitize_text_field(wp_unslash($_GET['nucleos_search'])) : '';
// Obtém a página solicitada via URL.
$requested_page = isset($_GET['paged'])
  ? absint($_GET['paged'])
  : (isset($_GET['page']) ? absint($_GET['page']) : 0);
// Recupera a página atual definida pelo WordPress (Query Vars).
$current_query_page = max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: 0));
// Define a página efetivamente utilizada na consulta,
// priorizando o parâmetro da URL e utilizando a Query do WordPress como fallback.
$paged = max(1, $requested_page ?: $current_query_page);
$nucleos_data = function_exists('fagroz_get_centers')
  ? fagroz_get_centers([
    'search' => $search_query,
    'paged' => $paged,
  ])
  : ['items' => [], 'pagination' => ''];
$nucleos = $nucleos_data['items'] ?? [];
$pagination = $nucleos_data['pagination'] ?? '';
?>
<section id="nossos-nucleos" class="page-sobre page-sobre__nossos-nucleos">
  <div class="container page-sobre__layout">
    <h2 class="page-sobre__title"><?php echo esc_html($section_title); ?></h2>
    <form class="page-sobre__filters" method="get" role="search">
      <input type="hidden" name="paged" value="1">
      <input
        type="search"
        class="page-sobre__search"
        id="nucleos-search"
        name="nucleos_search"
        placeholder="Pesquisar..."
        value="<?php echo esc_attr($search_query); ?>">
      <button type="submit" class="button button--secondary">Buscar</button>
    </form>
    <div class="page-sobre__empty" style="<?php echo !empty($nucleos) ? 'display: none;' : 'display: block;'; ?>">
      <?php echo esc_html($search_query ? 'Nenhum núcleo encontrado para sua busca.' : 'Nenhum núcleo encontrado.'); ?>
    </div>
    <?php if (!empty($nucleos)) : ?>
      <div class="page-sobre__nucleos-list">
        <?php foreach ($nucleos as $nucleo) :
          $nucleo_id = $nucleo['id'] ?? $nucleo['ID'] ?? get_the_ID();
          $legacyNucleusUrl = get_field('legacy_nucleus_url', $nucleo_id);
        ?>
          <article class="page-sobre__nucleo-card">
            <div class="page-sobre__nucleo-content">
              <h3 class="page-sobre__nucleo-title">
                <a href="<?php echo esc_url($nucleo['permalink']); ?>"><?php echo esc_html($nucleo['title']); ?></a>
              </h3>
              <p class="page-sobre__nucleo-excerpt"><?php echo wp_kses_post(wp_trim_words($nucleo['excerpt'], 24)); ?></p>
              <a class="button button--secondary page-sobre__nucleo-link" href="<?php echo esc_url($legacyNucleusUrl ?: $nucleo['permalink']); ?>" target="_blank" rel="noopener noreferrer">Ver mais</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <?php if (!empty($pagination)) : ?>
        <nav class="page-sobre__nucleos-pagination" aria-label="Paginação de núcleos">
          <?php echo wp_kses_post($pagination); ?>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>