<!-- 
  Exibe um post individual definido como padrão
  Ordem para chegar nesse arquivo:
  *single-post.php
  single.php
  singular.php
  index.php
-->
<?php
get_header();
while (have_posts()) :
  the_post();
  // $acfHeroImage = get_field('hero_image');
  // $title = $acfHeroImage['title'] ?: get_the_title();
  // $bg_photo = $acfHeroImage['bg_photo'] ?? '';
  // if (is_array($bg_photo)) {
  //   $bg_photo = $bg_photo['url'] ?? '';
  // }
  // if (empty($bg_photo)) {
  //   $bg_photo = get_the_post_thumbnail_url(get_the_ID(), 'full');
  // }
  // get_template_part(
  //   'template-parts/components/hero/post-hero-image',
  //   null,
  //   [
  //     'title' => $title,
  //     'image' => $bg_photo,
  //   ]
  // );
  $category_meta = fagroz_get_post_category_meta(get_the_ID(), 'sem-categoria');
  $tags = get_the_tags();
  $tag_ids = !empty($tags) ? wp_list_pluck($tags, 'term_id') : [];
  $related_posts = fagroz_get_related_posts(get_the_ID(), $tag_ids);
?>
  <div class="container container--single container--single-post page-section">
    <div class="single-article single-article--post">
      <main class="single-article__main">
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-article__entry'); ?>>
          <header class="single-article__header">
            <div class="metabox metabox--position-up metabox--with-home-link">
              <p>
                <a
                  class="metabox__blog-home-link"
                  href="<?php echo esc_url(home_url('/')); ?>"
                  aria-label="Voltar para a página inicial">
                  <span class="dashicons dashicons-admin-home"></span>
                </a>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <span class="metabox__main">
                  <?php
                  $posts_page_id = get_option('page_for_posts');
                  if ($posts_page_id) :
                  ?>
                    <a href="<?php echo esc_url(get_permalink($posts_page_id)); ?>">
                      <?php echo esc_html(get_the_title($posts_page_id)); ?>
                    </a>
                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                  <?php endif; ?>
                  <?php echo esc_html(get_the_date('d/m/Y')); ?>
                  às
                  <?php echo esc_html(get_the_time('H:i')); ?>
                </span>
              </p>
            </div>
            <span class="preview-card__label single-article__category <?php echo esc_attr($category_meta['class'] ?? ''); ?>">
              <?php echo esc_html($category_meta['text']); ?>
            </span>
            <?php the_title('<h1 class="single-article__title">', '</h1>'); ?>
          </header>

          <div class="entry-content single-article__content editorial-content">
            <?php the_content(); ?>
          </div>

          <?php if ($tags) : ?>
            <footer class="single-article__footer">
              <div class="post-taglines">
                <p class="post-taglines__title">Taglines:</p>
                <div class="post-taglines__list">
                  <?php foreach ($tags as $tag) : ?>
                    <span
                      class="post-taglines__item"
                      aria-label="Tagline: <?php echo esc_attr($tag->name); ?>">
                      <?php echo esc_html($tag->name); ?>
                    </span>
                  <?php endforeach; ?>
                </div>
              </div>
            </footer>
          <?php endif; ?>
        </article>
      </main>
      <aside class="single-article__sidebar">
        <?php
        get_template_part('template-parts/components/related-posts', null, [
          'title' => 'Leia também',
          'posts' => $related_posts,
          'show_label' => true,
        ]);
        ?>
      </aside>
    </div>
  </div>
<?php
endwhile;
get_footer();
