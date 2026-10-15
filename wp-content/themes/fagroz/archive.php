<!--
  Ordem de fallback do WordPress:
  archive-{slug}.php
  archive.php
  index.php
-->
<?php
get_header();
?>

<div class="container container--narrow page-section">
  <header class="archive-header">
    <h1 class="headline headline--medium">
      <?php echo esc_html(get_the_archive_title()); ?>
    </h1>

    <?php if (get_the_archive_description()) : ?>
      <div class="archive-description generic-content">
        <?php echo wp_kses_post(get_the_archive_description()); ?>
      </div>
    <?php endif; ?>
  </header>

  <?php if (have_posts()) : ?>
    <div class="archive-list">
      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('post-item'); ?>>
          <h2 class="headline headline--medium headline--post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>

          <div class="metabox">
            <p>
              <?php
              printf(
                esc_html__('Publicado por %s em %s.', 'fagroz'),
                get_the_author_posts_link(),
                esc_html(get_the_time('d/m/Y'))
              );
              ?>
            </p>
          </div>

          <div class="generic-content">
            <?php the_excerpt(); ?>
            <p>
              <a class="btn btn--blue" href="<?php the_permalink(); ?>">
                <?php esc_html_e('Continuar lendo', 'fagroz'); ?>
              </a>
            </p>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <?php the_posts_pagination(); ?>
  <?php else : ?>
    <p class="generic-content">
      <?php esc_html_e('Nenhum conteúdo encontrado.', 'fagroz'); ?>
    </p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>