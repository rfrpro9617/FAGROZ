<?php
$title = $args['title'] ?? '';
$subtitle = $args['subtitle'] ?? '';
$instagram_feed_page = get_page_by_path('feed-do-instagram');
$instagram_feed_url = $instagram_feed_page ? get_permalink($instagram_feed_page) : home_url('/feed-do-instagram/');
?>

<section class="social-media-news" aria-labelledby="social-media-news-title">
  <div class="social-media-news__inner">
    <div class="social-media-news__header" role="navigation" aria-label="Redes sociais e notícias">
      <p class="social-media-news__eyebrow" id="social-media-news-title"><?php echo esc_html($title); ?></p>

      <div class="social-media-news__social" aria-label="Redes sociais">
        <a href="<?php echo esc_url($instagram_feed_url); ?>" class="social-media-news__social-link" aria-label="Instagram">
          <span class="social-media-news__social-icon social-media-news__social-icon--instagram dashicons dashicons-instagram"></span>
        </a>
      </div>

      <p class="social-media-news__subtitle"><?php echo esc_html($subtitle); ?></p>
    </div>
  </div>
</section>