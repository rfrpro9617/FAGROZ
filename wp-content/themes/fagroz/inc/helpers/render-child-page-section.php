<?php

if (!function_exists('fagroz_render_child_page_section')) {
  function fagroz_render_child_page_section(string $slug, string $section_id, int $parent_id): void
  {
    $posts = get_posts(array(
      'post_type'      => 'page',
      'name'           => $slug,
      'post_parent'    => $parent_id,
      'post_status'    => 'publish',
      'posts_per_page' => 1,
    ));

    if (empty($posts)) {
      return;
    }

    $post = $posts[0];
    setup_postdata($post); ?>

    <article id="<?php echo esc_attr($section_id); ?>" class="dept-section">
      <div class="gutenberg-content editorial-content">
        <h1><?php echo esc_html(get_the_title($post)); ?></h1>
        <?php the_content(); ?>
      </div>
    </article>

<?php wp_reset_postdata();
  }
}
