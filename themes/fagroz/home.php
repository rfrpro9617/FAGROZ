<?php
get_header();

$home_data = fagroz_get_home_posts_data([
  'tag' => $_GET['tag'] ?? 'all',
  'search' => $_GET['search'] ?? '',
  'date' => $_GET['date'] ?? '',
]);

$posts = $home_data['posts'];
$pagination = $home_data['pagination'];
$tag = $home_data['tag'];
$search = $home_data['search'];
$date = $home_data['date'];

$tag_terms = get_tags([
  // Elimina tags que não possuem posts associados
  'hide_empty' => true,
  'orderby' => 'name',
  'order' => 'ASC',
]);
?>

<?php
$blog_id = get_option('page_for_posts');
$title = get_the_title($blog_id);
$thumbnail = get_the_post_thumbnail_url($blog_id, 'full');

$posts_page_url = $blog_id ? get_permalink($blog_id) : home_url('/');

get_template_part(
  'template-parts/components/hero/post-hero-image',
  null,
  [
    'title' => $title,
    'image' => $thumbnail,
    'text_position' => 'center',
  ]
);
?>

<?php $has_posts = !empty($posts); ?>

<section class="home-posts <?php echo $has_posts ? '' : 'home-posts--empty'; ?>">
  <div class="home-posts__inner container">
    <div class="home-posts__header">
      <form
        class="home-posts__controls"
        method="get"
        action="<?php echo esc_url($posts_page_url); ?>">
        <div class="home-posts__filters-area">
          <span class="home-posts__filters-label">Filtros</span>
          <div class="home-posts__filter-row">
            <div class="home-posts__filter home-posts__filter--date">
              <label>
                <span>Data</span>
                <input
                  type="date"
                  name="date"
                  value="<?php echo esc_attr($date); ?>"
                  onchange="this.form.submit()">
              </label>
            </div>
            <div class="home-posts__filter home-posts__filter--tag">
              <label>
                <span>Conteúdo</span>
                <select name="tag" onchange="this.form.submit()">
                  <option value="all" <?php selected($tag, 'all'); ?>>Todos</option>
                  <?php if (!is_wp_error($tag_terms)) : ?>
                    <?php foreach ($tag_terms as $tag_term) : ?>
                      <option
                        value="<?php echo esc_attr($tag_term->slug); ?>"
                        <?php selected($tag, $tag_term->slug); ?>>
                        <?php echo esc_html($tag_term->name); ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </label>
            </div>
          </div>
        </div>
        <div class="home-posts__search-area">
          <div class="home-posts__search">
            <input
              type="search"
              name="search"
              value="<?php echo esc_attr($search); ?>"
              placeholder="Pesquisar...">
          </div>
          <button class="home-posts__submit" type="submit">Buscar</button>
          <button
            class="home-posts__submit home-posts__submit--secondary"
            type="button"
            onclick="this.form.elements['date'].value=''; this.form.elements['tag'].value='all'; this.form.elements['search'].value=''; this.form.submit();">
            Limpar filtros
          </button>
        </div>
      </form>
    </div>
    <div class="home-posts__grid">
      <?php if ($has_posts) : ?>
        <?php foreach ($posts as $post) : ?>
          <?php
          $label_class = $post['label_class'] ?? '';
          $label_modifier = str_replace(
            'preview-card__label--',
            'home-posts__label--',
            $label_class
          );
          ?>
          <article
            class="home-posts__card"
            style="background-image: url('<?php echo esc_url($post['thumbnail']); ?>');">
            <div class="home-posts__card-top">
              <span class="home-posts__label <?php echo esc_attr($label_modifier); ?>">
                <?php echo esc_html($post['label_text']); ?>
              </span>
              <span class="home-posts__date-pill">
                <?php echo esc_html($post['date']); ?>
              </span>
            </div>
            <h2 class="home-posts__card-title">
              <a href="<?php echo esc_url($post['permalink']); ?>">
                <?php echo esc_html($post['title']); ?>
              </a>
            </h2>
            <p class="home-posts__card-excerpt">
              <?php echo esc_html($post['excerpt']); ?>
            </p>
            <a
              class="home-posts__button"
              href="<?php echo esc_url($post['permalink']); ?>">
              Ver mais
            </a>
          </article>
        <?php endforeach; ?>
      <?php else : ?>
        <div class="home-posts__empty-message">
          <p>Nenhuma notícia encontrada.</p>
        </div>
      <?php endif; ?>
    </div>
    <?php if ($pagination) : ?>
      <div class="home-posts__pagination">
        <?php echo $pagination; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>