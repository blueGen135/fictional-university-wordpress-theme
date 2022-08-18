<?php
get_header();
  while (have_posts()) {
      the_post();
       pageBanner(array(
        'title' => '',
        'subtitle' => '',
        'photo' => ''
      ));
?>

<div class="container container--narrow page-section">
  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>
      <a class="metabox__blog-home-link" href=" <?php echo get_post_type_archive_link('campus') ?> "><i class="fa fa-home" aria-hidden="true"></i>All Campuses</a> <span class="metabox__main"><?php the_title(); ?></span>
    </p>
  </div>
  <div class="map_location">
    <?php the_field('location_map') ?>

  </div>
  <div class="generic-content">  <?php the_content(); ?></div>
  <?php

  $today = date('Ymd');

  $relatedPrograms = new WP_Query(array(
      'post_type' => 'program',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
      'meta_query' => array(
        array(
          'key'=> 'related_campus',
          'compare' => 'LIKE',
          'value' =>'"'. get_the_ID(). '"'
        )
      )
  ));
  if ($relatedPrograms->have_posts()):

  echo '<hr class="section-break">'  ;
    echo '<h2 class="headline headline--medium">Programs available at this campus</h2>';
  echo '<ul class="link-list min-list">';
  while($relatedPrograms->have_posts()){
    $relatedPrograms->the_post(); ?>
      <li class="">
        <a  href="<?php the_permalink(); ?>">

            <?php the_title(); ?>

        </a>
      </li>
<?php  } echo '</ul>'; wp_reset_postdata(); endif; ?>



</div>
<?php }
get_footer();
?>
