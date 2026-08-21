<!-- 
  Ordem para chegar nesse arquivo:
  single-post.php          ← Post padrão
  single-professor.php     ← CPT professor
  single-curso.php         ← CPT curso
  single.php               ← Fallback
  singular.php             ← Fallback ainda mais genérico
  *index.php               ← Último recurso
-->
<?php
get_header();
if (have_posts()) :
  while (have_posts()) :
    the_post();

    get_template_part(
      'template-parts/content',
      get_post_type()
    );
  endwhile;
else :
  get_template_part('template-parts/content', 'none');
endif;
get_footer();
?>