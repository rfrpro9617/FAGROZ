<?php
/**
 * Template Part: Breadcrumbs
 *
 * Exibe um caminho de navegação com ícone de home e os itens passados.
 *
 * Args:
 * - items (array): Lista de itens do breadcrumb.
 *   Cada item deve ser ['label' => string, 'url' => string|null].
 */
$items = $args['items'] ?? [];
if (empty($items) || !is_array($items)) {
  return;
}
?>
<div class="metabox metabox--position-up metabox--with-home-link">
  <p>
    <a
      class="metabox__blog-home-link"
      href="<?php echo esc_url(home_url('/')); ?>"
      aria-label="<?php esc_attr_e('Voltar para a página inicial', 'fagroz'); ?>">
      <span class="dashicons dashicons-admin-home"></span>
    </a>
    <span class="dashicons dashicons-arrow-right-alt2"></span>
    <span class="metabox__main">
      <?php foreach ($items as $index => $item) :
        $label = $item['label'] ?? '';
        $url = $item['url'] ?? '';
        if ($index) : ?>
          <span class="dashicons dashicons-arrow-right-alt2"></span>
        <?php endif; ?>
        <span class="metabox__crumb">
          <?php if (!empty($url)) : ?>
            <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></a>
          <?php else : ?>
            <?php echo esc_html($label); ?>
          <?php endif; ?>
        </span>
      <?php endforeach; ?>
    </span>
  </p>
</div>
