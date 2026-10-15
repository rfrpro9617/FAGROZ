<?php
$title = $args['title'] ?? '';
$image = $args['bg_photo'] ?? $args['image'] ?? '';
$text_position = $args['text_position'] ?? 'bottom-left';
$compactMargin = $args['compact_margin'] ?? false;
$hasImage = !empty($image);

if (empty($title) && !$hasImage) {
  return;
}

$heroClass = 'hero-image' . ($compactMargin ? ' hero-image--compact-margin' : '') . (!$hasImage ? ' hero-image--text-only' : '');
?>

<section class="<?php echo esc_attr($heroClass); ?>">
  <div class="hero-image__media<?php echo $hasImage ? '' : ' hero-image__media--text-only'; ?>"<?php if ($hasImage) : ?> style="background-image: url('<?php echo esc_url($image); ?>');"<?php endif; ?> >
    <?php if ($hasImage) : ?>
      <div class="hero-image__overlay"></div>
    <?php endif; ?>

    <div class="hero-image__content hero-image__content--<?php echo esc_attr($text_position); ?>">
      <div class="container">
        <?php if (!empty($title)) : ?>
          <h2 class="hero-image__title">
            <?php echo esc_html($title); ?>
          </h2>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>