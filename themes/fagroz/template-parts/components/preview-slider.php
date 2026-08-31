<?php
$title = $args['title'] ?? '';
$query_news = $args['query_news'] ?? [];
$show_tags = isset($args['show_tags']) ? (bool) $args['show_tags'] : true;
$use_href = isset($args['use_href']) ? (bool) $args['use_href'] : true;
?>

<section class="home-news">
  <p class="home-news__title">
    <?php echo esc_html($title); ?>
  </p>
  <div class="swiper preview-slider">
    <div class="swiper-wrapper">
      <?php foreach ($query_news as $item) :
        $excerpt = $item['excerpt'] ?? '';
        if (empty($excerpt) && !empty($item['title'])) {
          $excerpt = $item['title'];
        }
        $wrapper_tag = $use_href ? 'a' : 'div';
      ?>
        <<?php echo $wrapper_tag; ?>
          class="swiper-slide preview-card__link"
          <?php if ($use_href) : ?>
          href="<?php echo esc_url($item['permalink']); ?>"
          target="_blank"
          rel="noopener noreferrer"
          <?php endif; ?>
          style="background-image: url('<?php echo esc_url($item['thumbnail']); ?>');">
          <div class="preview-card__labels">
            <?php if ($show_tags) : ?>
              <?php if (!empty($item['label_text'])) : ?>
                <span class="preview-card__label <?php echo esc_attr($item['label_class']); ?>">
                  <?php echo esc_html($item['label_text']); ?>
                </span>
              <?php endif; ?>
              <span class="preview-card__label">
                <?php echo esc_html($item['date']); ?>
              </span>
            <?php endif; ?>
          </div>
          <p class="preview-card__description">
            <?php echo esc_html($excerpt); ?>
          </p>
          <span class="preview-card__more">
            Ver mais
          </span>
        </<?php echo $wrapper_tag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section>