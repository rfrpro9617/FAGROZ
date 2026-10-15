<?php
get_header();
if (have_posts()) :
  while (have_posts()) :
    the_post();

    $content = apply_filters('the_content', get_the_content());
?>
    <div class="container container--single container--single-cpt page-section">
      <div class="single-article single-article--editorial">
        <main class="single-article__main">
          <article id="post-<?php the_ID(); ?>" <?php post_class('single-article__entry'); ?>>
            <div class="single-article__content editorial-content">
              <?php echo do_shortcode('[trustindex-feed-instagram]'); ?>
            </div>
          </article>
        </main>
      </div>
    </div>
<?php
  endwhile;
endif;
get_footer();
