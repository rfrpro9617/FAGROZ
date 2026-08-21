<?php
$title = $args['title'] ?? '';
$posts = $args['posts'] ?? [];
$show_label = $args['show_label'] ?? false;

if (empty($posts)) {
  return;
}
?>
<div class="related-posts__header">
  <p class="related-posts__heading"><?php echo esc_html($title); ?></p>
</div>
<div class="related-posts">
  <?php foreach ($posts as $post) : ?>
    <a
      class="related-card"
      href="<?php echo esc_url($post['permalink']); ?>"
      style="background-image: url('<?php echo esc_url($post['thumbnail']); ?>');"
      aria-label="<?php echo esc_attr($post['title']); ?>">
      <div class="related-card__labels">
        <?php if ($show_label) : ?>
          <span class="preview-card__label <?php echo esc_attr($post['label_class'] ?? ''); ?>">
            <?php echo esc_html($post['label_text']); ?>
          </span>
        <?php endif; ?>
        <span class="preview-card__label related-card__date--pill">
          <?php echo esc_html($post['date']); ?>
        </span>
      </div>
      <div class="related-card__content">
        <?php
        $card_title = $post['title'];
        if (mb_strlen($card_title) > 60) {
          $card_title = wp_strip_all_tags($post['excerpt']);
          $card_title = wp_trim_words($card_title, 12, '...');
        }
        ?>
        <h3 class="related-card__title"><?php echo esc_html($card_title); ?></h3>
      </div>
    </a>
  <?php endforeach; ?>
</div>