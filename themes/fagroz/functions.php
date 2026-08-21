<?php

/**
 * Arquivo principal de funções do tema.
 * Este arquivo reúne as configurações que tornam o WordPress mais personalizado.
 */

// --- Carregamento de arquivos de banco de dados, queries e helpers ---
require_once get_template_directory() . '/inc/database/external-db.php';
require_once get_template_directory() . '/inc/helpers/render-child-page-section.php';
require_once get_theme_file_path('/inc/helpers/category-label.php');
require_once get_theme_file_path('/inc/helpers/render-child-page-section.php');
require_once get_template_directory() . '/inc/queries/repo-intranet.php';
require_once get_theme_file_path('/inc/queries/repo-post.php');
require_once get_theme_file_path('/inc/queries/single-post-query.php');
require_once get_theme_file_path('/inc/queries/repo-graduation.php');
require_once get_theme_file_path('/inc/queries/repo-nucleos.php');
require_once get_theme_file_path('/inc/queries/repo-documentos.php');
require_once get_theme_file_path('/inc/constants.php');

// --- 1. Estrutura do menu mega ---
class Fagroz_Mega_Menu_Walker extends Walker_Nav_Menu
{
  function start_lvl(&$output, $depth = 0, $args = null)
  {
    if ($depth === 0) {
      $output .= "\n<div class=\"mega-menu\">\n<div class=\"mega-menu__inner\">\n<ul class=\"sub-menu\">\n";
    } elseif ($depth === 1) {
      $output .= "\n<ul class=\"sub-menu-column\">\n";
    }
  }

  function end_lvl(&$output, $depth = 0, $args = null)
  {
    if ($depth === 0) {
      $output .= "\n</ul>\n</div>\n</div>\n";
    } elseif ($depth === 1) {
      $output .= "\n</ul>\n";
    }
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $classes = empty($item->classes) ? array() : (array) $item->classes;
    if ($args->walker->has_children) {
      $classes[] = 'menu-item-has-children';
    }
    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

    $output .= "<li" . $class_names . ">";

    $atts = array();
    $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
    $atts['target'] = ! empty($item->target)     ? $item->target     : '';
    $atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
    $atts['href']   = ! empty($item->url)        ? $item->url        : '';
    $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

    $attributes = '';
    foreach ($atts as $attr => $value) {
      if (! empty($value)) {
        $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }

    $title = apply_filters('the_title', $item->title, $item->ID);

    $item_output = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .= $args->link_before . $title . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}

// --- 2. Endpoints personalizados da API REST usada no Search do Header ---
// Este bloco cria uma busca customizada para retornar resultados em formato simples.
function universitySearchResults($data)
{
  $term = sanitize_text_field($data['term'] ?? '');
  $page = max(1, absint($data['page'] ?? 1));
  $perPage = max(1, min(10, absint($data['per_page'] ?? 5)));

  if ($term === '') {
    return array(
      'results' => array(),
      'current_page' => 1,
      'total_pages' => 1,
      'total_items' => 0,
      'per_page' => $perPage,
    );
  }

  $mainQuery = new WP_Query(array(
    'post_type' => 'post',
    's' => $term,
    'posts_per_page' => $perPage,
    'paged' => $page,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
  ));

  $results = array();

  while ($mainQuery->have_posts()) {
    $mainQuery->the_post();
    $postType = get_post_type();
    $postTypeObject = get_post_type_object($postType);

    $results[] = array(
      'title' => get_the_title(),
      'link'  => get_permalink(),
      'type'  => $postType,
      'typeLabel' => $postTypeObject ? $postTypeObject->labels->singular_name : '',
      'author' => get_the_author(),
      'excerpt' => wp_trim_words(get_the_excerpt(), 20),
      'orderby' => 'date',
      'order' => 'DESC'
    );
  }

  wp_reset_postdata();

  return array(
    'results' => $results,
    'current_page' => $page,
    'total_pages' => max(1, (int) ceil($mainQuery->found_posts / $perPage)),
    'total_items' => (int) $mainQuery->found_posts,
    'per_page' => $perPage,
  );
}

add_action('rest_api_init', function () {
  register_rest_route('university/v1', 'search', array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'universitySearchResults',
    'args' => array(
      'term' => array(
        'required' => true,
        'sanitize_callback' => 'sanitize_text_field',
      ),
      'page' => array(
        'sanitize_callback' => 'absint',
      ),
      'per_page' => array(
        'sanitize_callback' => 'absint',
      ),
    ),
  ));
});

function university_custom_rest()
{
  // Tipo de conteúdo que desejamos modificar, Nome do campo, Callback
  register_rest_field('post', 'authorName', array(
    'get_callback' => function () {
      return get_the_author();
    }
  ));
}

add_action('rest_api_init', 'university_custom_rest');

// --- 3. Recursos e assets do tema (CSS/JS) ---
// Aqui são carregados os arquivos de estilo e script usados no site.
function load_theme_resources()
{
  wp_enqueue_script('load-fagroz-js', get_theme_file_uri('/build/index.js'), array('jquery', 'swiper-js'), '1.0', true);
  wp_enqueue_style('google-fonts-figtree', 'https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap');
  wp_enqueue_style('google-fonts-poppins', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
  wp_enqueue_style(
    'swiper-css',
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
    array(),
    '11.2.10'
  );
  wp_enqueue_script(
    'swiper-js',
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
    array(),
    '11.2.10',
    true
  );
  wp_enqueue_style('dashicons');
  wp_enqueue_style('fagroz_styles', get_theme_file_uri('/build/style-index.css'));
  wp_localize_script('load-fagroz-js', 'universityData', array(
    'root_url' => get_site_url(),
  ));
}

add_action('wp_enqueue_scripts', 'load_theme_resources');

// --- 4. Configurações básicas do tema ---
// Define menus, suporte a títulos e imagens destacadas.
function university_features()
{
  register_nav_menu('headerMenuLocation', 'Header Menu Location');
  add_theme_support('title-tag');
  // Habilita suporte a imagens destacadas (thumbnails) para posts e páginas (imagem de fundo)
  add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'university_features');

// --- 5. Personalização do painel administrativo ---
// Altera nomes e rótulos do painel para deixar o WordPress mais adequado ao tema.
function fagroz_rename_posts()
{
  global $menu, $submenu, $wp_post_types;

  // Altera os labels do post type "post"
  $labels = &$wp_post_types['post']->labels;

  $labels->name = 'Notícias';
  $labels->singular_name = 'Notícia';
  $labels->add_new = 'Adicionar';
  $labels->add_new_item = 'Adicionar Notícia';
  $labels->edit_item = 'Editar Notícia';
  $labels->new_item = 'Nova Notícia';
  $labels->view_item = 'Ver Notícia';
  $labels->search_items = 'Buscar Notícias';
  $labels->not_found = 'Nenhuma notícia encontrada';
  $labels->not_found_in_trash = 'Nenhuma notícia encontrada na lixeira';
  $labels->all_items = 'Todas as Notícias';
  $labels->menu_name = 'Notícias';
  $labels->name_admin_bar = 'Notícia';

  // Renomeia o menu do painel
  $menu[5][0] = 'Notícias';
  $submenu['edit.php'][5][0] = 'Todas as Notícias';
  $submenu['edit.php'][10][0] = 'Adicionar Notícia';
}

add_action('init', 'fagroz_rename_posts');
add_action('admin_menu', 'fagroz_rename_posts');

// --- 6. Tipos de conteúdo personalizados ---
// Cria conteúdos especiais para cada curso, como destaques e páginas específicas.
function university_post_types()
{
  register_post_type('graduation', array(
    // Mostra no retorno do json da api de posts
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
    'rewrite' => array('slug' => 'graduation'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Cursos de Graduação',
      'add_new_item' => 'Adicionar novo curso de graduação',
      'edit_item' => 'Editar curso de graduação',
      'all_items' => 'Todos os cursos de graduação',
      'singular_name' => 'Curso de Graduação',
    ),
  ));
  register_post_type('graduation-highlight', array(
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
    'rewrite' => array('slug' => 'graduation-highlights'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Destaques da Graduação',
      'add_new_item' => 'Adicionar novo destaque',
      'edit_item' => 'Editar destaque',
      'all_items' => 'Todos os destaques',
      'singular_name' => 'Destaque da Graduação',
    ),
  ));
  register_post_type('research-group', array(
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
    'rewrite' => array('slug' => 'research-group'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Grupos de Pesquisa',
      'add_new_item' => 'Adicionar novo grupo de pesquisa',
      'edit_item' => 'Editar grupo de pesquisa',
      'all_items' => 'Todos os grupos de pesquisa',
      'singular_name' => 'Grupo de Pesquisa',
    ),
  ));
  register_post_type('postgraduate', array(
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
    'rewrite' => array('slug' => 'postgraduate'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Cursos de Pós-Graduação',
      'add_new_item' => 'Adicionar curso de Pós-Graduação',
      'edit_item' => 'Editar curso de Pós-Graduação',
      'all_items' => 'Todos os Cursos de Pós-Graduação',
      'singular_name' => 'Curso de Pós-Graduação',
    ),
  ));
  register_post_type('departments', array(
    // Mostra no retorno do json da api de posts
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
    'rewrite' => array('slug' => 'departments'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Departamentos',
      'add_new_item' => 'Adicionar novo departamento',
      'edit_item' => 'Editar departamento',
      'all_items' => 'Todos os departamentos',
      'singular_name' => 'Departamento',
    ),
  ));
  register_post_type('nucleos', array(
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
    'taxonomies' => array('post_tag'),
    'rewrite' => array('slug' => 'nucleos'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Núcleos',
      'add_new_item' => 'Adicionar novo núcleo',
      'edit_item' => 'Editar núcleo',
      'all_items' => 'Todos os núcleos',
      'singular_name' => 'Núcleo',
    ),
  ));
  register_post_type('documentos', array(
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
    'taxonomies' => array('post_tag', 'category'),
    'rewrite' => array('slug' => 'documentos'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Documentos',
      'add_new_item' => 'Adicionar novo documento',
      'edit_item' => 'Editar documento',
      'all_items' => 'Todos os documentos',
      'singular_name' => 'Documento',
    ),
  ));
}

add_action('init', 'university_post_types');

// --- 8. Ajustes do editor visual e do painel ---
// Remove a barra administrativa na parte pública do site.
add_filter('show_admin_bar', '__return_false');

// Ativa opções extras no editor visual do WordPress.
function fagroz_mce_buttons($buttons)
{
  return array(
    'formatselect',
    'fontselect',
    'fontsizeselect',
    'bold',
    'italic',
    'underline',
    'strikethrough',
    'forecolor',
    'backcolor',
    'bullist',
    'numlist',
    'blockquote',
    'alignleft',
    'aligncenter',
    'alignright',
    'alignjustify',
    'outdent',
    'indent',
    'link',
    'unlink',
    'hr',
    'charmap',
    'removeformat',
    'undo',
    'redo',
    'pastetext',
    'fullscreen',
    'wp_adv',
  );
}
add_filter('mce_buttons', 'fagroz_mce_buttons');


function fagroz_mce_buttons_2($buttons)
{
  return array(
    'styleselect',

    'subscript',
    'superscript',
    'copy',
    'cut',
    'paste',
    'visualblocks',
    'visualchars',
    'searchreplace',
    'nonbreaking',
    'spellchecker',
    'wp_help',
  );
}

add_filter('mce_buttons_2', 'fagroz_mce_buttons_2');

// --- 9. Ajustes de busca e listagens ---
// Remove páginas de determinadas consultas para mostrar apenas os conteúdos desejados.
function fagroz_excluir_pages_das_consultas($query)
{
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  // Tipos de conteúdo que devem ser exibidos na busca do site
  if ($query->is_search()) {
    $query->set('post_type', [
      'post',
    ]);
  }
}

add_action('pre_get_posts', 'fagroz_excluir_pages_das_consultas');
