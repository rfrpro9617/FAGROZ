<section class="hero-slider swiper">
  <div class="swiper-wrapper">
    <?php foreach ($args['slides'] as $slide): ?>
      <?php if (!empty($slide['img'])): ?>
        <div class="swiper-slide hero-slide">
          <?php $has_link = !empty($slide['link']); ?>
          <?php if ($has_link): ?>
            <a href="<?php echo esc_url($slide['link']); ?>" class="hero-slide__link" target="_blank">
            <?php endif; ?>
            <img
              src="<?php echo esc_url($slide['img']['url']); ?>"
              alt="<?php echo esc_attr($slide['title']); ?>">
            <div class="hero-slide__overlay"></div>
            <div class="hero-slide__content">
              <div class="container">
                <h2><?php echo esc_html($slide['title']); ?></h2>
              </div>
            </div>
            <?php if ($has_link): ?>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
  <div class="hero-slider__controls">
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
  </div>
</section>