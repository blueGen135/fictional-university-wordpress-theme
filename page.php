<?php
get_header();
  while (have_posts()) {
      the_post();
      pageBanner( array(
       'title' => '',
       'subtitle' => '',
       'photo' => ''
     )
      );
?>


  <div class="container container--narrow page-section">
    <?php
    $theParent = wp_get_post_parent_id( get_the_ID());
    if($theParent): ?>
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?=get_permalink( $theParent )?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?=get_the_title($theParent)?></a> <span class="metabox__main"><?php the_title(); ?></span>
      </p>
    </div>
    <?php endif; ?>
    <?php
    $findParent = get_pages(array(
      'child_of' => get_the_ID()
    ));
    if($theParent or $findParent): ?>
    <div class="page-links">
      <h2 class="page-links__title"><a href="<?=get_permalink($theParent )?>"><?=get_the_title( $theParent )?></a></h2>
      <ul class="min-list">
        <?php
        if($theParent){
          $findChildOf = $theParent;
        }else{
          $findChildOf = get_the_ID();
        }
            wp_list_pages(array(
              'title_li' => NULL,
              'child_of' => $findChildOf,
              'sort_column' => 'menu_order'
            ));
        ?>
      </ul>
    </div>
<?php endif; ?>
    <div class="generic-content">
        <?php the_content(); ?>
    </div>
  </div>

<?php }
get_footer();
?>
