<?php get_header( );
pageBanner(array(
  'title' => 'Our Campuses',
  'subtitle' => 'We have several conveniently located campuses.',
  'photo' => ''
));
 ?>


<div class="container container--narrow page-section">
  <ul class="link-list min-list">

<?php
    while (have_posts()) {
      the_post(); ?>
      <li> <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
        <div class="map_location">
          <?php the_field('location_map') ?>

        </div>
      </li>
<?php  }
// echo paginate_links( );
?>
</ul>

</div>
<?php get_footer(); ?>
