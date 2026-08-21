<?php
$title = $args['title'] ?? '';
$query_news = $args['query_news'] ?? [];
?>
<section class="home-news">
  <p class="home-news__title"><?php echo esc_html($title); ?></p>
  <div class="swiper preview-slider">
    <div class="swiper-wrapper">
      <?php foreach ($query_news as $item) : ?>
        <a
          class="swiper-slide preview-card__link"
          href="<?php echo esc_url($item['permalink']); ?>"
          style="background-image: url('<?php echo esc_url($item['thumbnail']); ?>');">
          <div class="preview-card__labels">
            <span class="preview-card__label <?php echo esc_attr($item['label_class']); ?>">
              <?php echo esc_html($item['label_text']); ?>
            </span>
            <span class="preview-card__label">
              <?php echo esc_html($item['date']); ?>
            </span>
          </div>
          <p class="preview-card__description">
            <?php echo esc_html($item['excerpt']); ?>
          </p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>