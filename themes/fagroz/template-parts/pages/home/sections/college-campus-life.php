<?php
$title = $args['title'];
$description = $args['description'];
$bg_photo = $args['bg_photo'];
$compactMargin = $args['compact_margin'] ?? false;
?>
<section class="college-campus-life">
  <?php
  get_template_part(
    'template-parts/components/hero/post-hero-image',
    null,
    [
      'title' => $title,
      'image' => $bg_photo,
      'compact_margin' => $compactMargin,
    ]
  );
  ?>
  <div class="container">
    <div class="college-campus-life__content">
      <div class="college-campus-life__text">
        <span class="college-campus-life__paragraph"><?php echo wp_kses_post($description); ?></span>
      </div>
    </div>
  </div>
</section>