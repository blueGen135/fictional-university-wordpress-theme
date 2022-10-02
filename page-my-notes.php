<?php
if (!is_user_logged_in()) {
  wp_redirect( esc_url(site_url('/')));
  exit();
}
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
    <div class="create-note">
      <h2 class="headline headline--medium">Create New Note</h2>
    <input placeholder="Title" class="new-note-title">
    <textarea class="new-note-body" placeholder="Your note here...."></textarea>
    <span class="submit-note">Create Note</span>
    </div>
      <ul class="min-list link-list" id="my-notes">
        <?php
          $userNotes = new WP_Query(array(
              'post_type' => 'note',
              'posts_per_page' => -1,
              'author' => get_current_user_id()
          ));
          while ($userNotes-> have_posts()) {
            $userNotes->the_post(); ?>
            <li data-id="<?php echo the_id() ?>" >
                <input class="note-title-field" readonly value="<?php echo esc_attr(get_the_title())?>">
                <span class="edit-note"><i class="fa fa-pencil" area-hidden="true"></i> Edit</span>
                <span class="delete-note"><i class="fa fa-trash-o" area-hidden="true"></i> Delete</span>
                <textarea name="name" readonly class="note-body-field">
                  <?php echo esc_attr(wp_strip_all_tags( get_the_content() )) ?>
                </textarea>
                <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" area-hidden="true"></i> Save</span>


            </li>
        <?php  } ?>
      </ul>
  </div>

<?php }
get_footer();
?>
