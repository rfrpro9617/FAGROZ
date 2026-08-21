<?php
$search_query = isset($_GET['documentos_search']) ? sanitize_text_field(wp_unslash($_GET['documentos_search'])) : '';
$nucleos_data = function_exists('fagroz_get_documentos_items') ? fagroz_get_documentos_items(['search' => $search_query]) : ['items' => [], 'pagination' => ''];
$nucleos = $nucleos_data['items'] ?? [];
$pagination = $nucleos_data['pagination'] ?? '';
?>
<section id="documentos-oficiais" class="section page-sobre page-sobre__nossos-nucleos">
  <div class="container page-sobre__layout">
    <h2 class="page-sobre__title">Documentos</h2>
    <form class="page-docentes__filters" method="get" role="search">
      <input
        type="search"
        class="page-docentes__search"
        id="documentos-search"
        name="documentos_search"
        placeholder="Pesquisar..."
        value="<?php echo esc_attr($search_query); ?>">
      <button type="submit" class="button button--secondary">Buscar</button>
    </form>
    <?php if (!empty($nucleos)) : ?>
      <div class="page-sobre__nucleos-list" id="documentos-list">
        <?php foreach ($nucleos as $nucleo) : ?>
          <article class="page-sobre__nucleo-card">
            <?php if (!empty($nucleo['thumbnail'])) : ?>
              <a class="page-sobre__nucleo-media" href="<?php echo esc_url($nucleo['document_url'] ?? $nucleo['permalink']); ?>">
                <img src="<?php echo esc_url($nucleo['thumbnail']); ?>" alt="<?php echo esc_attr($nucleo['title']); ?>">
              </a>
            <?php endif; ?>
            <div class="page-sobre__nucleo-content">
              <h3 class="page-sobre__nucleo-title">
                <a href="<?php echo esc_url($nucleo['document_url'] ?? $nucleo['permalink']); ?>"><?php echo esc_html($nucleo['title']); ?></a>
              </h3>
              <p class="page-sobre__nucleo-excerpt"><?php echo wp_kses_post(wp_trim_words($nucleo['excerpt'], 24)); ?></p>
              <a class="button button--secondary page-sobre__nucleo-link" href="<?php echo esc_url($nucleo['document_url'] ?? $nucleo['permalink']); ?>">Ver mais</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <?php if (!empty($pagination)) : ?>
        <nav class="page-sobre__nucleos-pagination" aria-label="Paginação de documentos">
          <?php echo wp_kses_post($pagination); ?>
        </nav>
      <?php endif; ?>
    <?php else : ?>
      <p><?php echo esc_html($search_query ? 'Nenhum documento encontrado para sua busca.' : 'Ainda não há documentos cadastrados.'); ?></p>
    <?php endif; ?>
  </div>
</section>