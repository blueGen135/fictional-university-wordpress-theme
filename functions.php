<?php

require get_theme_file_path('/inc/search-route.php');

function university_custom_rest(){
  register_rest_field('post', 'authorName', array(
      'get_callback' => function () { return get_the_author(); }
  ));
}

add_action( 'rest_api_init',  'university_custom_rest');

function pageBanner($args = NULL) {

  if (!$args['title']) {
    $args['title'] = get_the_title();
  }

  if (!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!$args['photo']) {
    if (get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
      $args['photo'] = get_field('page_banner_background_image');
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }

  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>
  </div>
<?php }

function university_files(){
  wp_enqueue_script( 'main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
  wp_enqueue_style( 'custom-google-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style( 'university_main_style', get_theme_file_uri('/build/style-index.css') );
  wp_enqueue_style( 'university_extra_style', get_theme_file_uri('/build/index.css') );
  wp_localize_script('main-university-js', 'universityData', array(
    'root_url' => get_site_url(),
    'nonce' => wp_create_nonce( 'wp_rest' )
  ));
}
add_action( 'wp_enqueue_scripts', 'university_files' );
//Add appropriate page title


function university_features() {
  register_nav_menu( 'headerMenuLocation', 'Header Menu Location' );
  register_nav_menu( 'footerLocationOne', 'Footer Location One' );
  register_nav_menu( 'footerLocationTwo', 'Footer Location Two' );
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size( 'professorLandscape', 400, 260 , true );
  add_image_size( 'professorPortrait', 480, 650 , true );
  add_image_size( 'pageBanner', 1500, 350 , true );
}

add_action('after_setup_theme', 'university_features');

/*Custom Post Type*/
function university_post_type (){
  //Campus Post Type
  register_post_type('campus', array(
  'show_in_rest' => true,
  'supports' => array('title', 'editor', 'excerpt'),
  'rewrite' => array('slug' => 'campuses'),
  'has_archive' => true,
  'public' => true,
  'labels' => array(
    'name' => 'Campuses',
    'add_new_item' => 'Add New Campus',
    'edit_item' => 'Edit Campus',
    'all_items' => 'All Campuses',
    'singular_name' => 'Campus'
  ),
  'menu_icon' => 'dashicons-location-alt'
));
//Event Post Type
  register_post_type( 'event', array(
      'capability_type' => 'event',
      'map_meta_cap' => true,
      'supports' => array('title', 'editor', 'excerpt'),
      'rewrite' => array('slug' => 'events'),
      'has_archive' => true,
      'public' => true,
      'show_in_rest' => true,
      'labels' => array(
        'name' => 'Events',
        'add_new_item' => 'Add New Event',
        'edit_item' => 'Edit Event',
        'all_items' => 'All Events',
        'singular_name' => 'Event'
      ),
      'menu_icon' => 'dashicons-calendar-alt'
  ) );

  //Program post type

  register_post_type( 'program', array(
      'supports' => array('title'),
      'rewrite' => array('slug' => 'programs'),
      'has_archive' => true,
      'public' => true,
      'show_in_rest' => true,
      'labels' => array(
        'name' => 'Programs',
        'add_new_item' => 'Add New Program',
        'edit_item' => 'Edit Program',
        'all_items' => 'All Programs',
        'singular_name' => 'Program'
      ),
      'menu_icon' => 'dashicons-awards'
  ) );

  //Professor post type

  register_post_type( 'professor', array(
      'supports' => array('title', 'editor','thumbnail'),
      'public' => true,
      'show_in_rest' => true,
      'labels' => array(
        'name' => 'Professors',
        'add_new_item' => 'Add New Professor',
        'edit_item' => 'Edit Professor',
        'all_items' => 'All Professors',
        'singular_name' => 'Professor'
      ),
      'menu_icon' => 'dashicons-welcome-learn-more'
  ) );

  //Professor post type

  register_post_type( 'note', array(
      'capability_type' => 'note',
      'map_meta_cap' => true,
      'supports' => array('title', 'editor'),
      'public' => false,
      'show_ui' => true,
      'show_in_rest' => true,
      'labels' => array(
        'name' => 'Notes',
        'add_new_item' => 'Add New Note',
        'edit_item' => 'Edit Note',
        'all_items' => 'All Notes',
        'singular_name' => 'Note'
      ),
      'menu_icon' => 'dashicons-welcome-write-blog'
  ) );

}
add_action( 'init', 'university_post_type' );

//Custom query for events
function university_adjust_queries($query){
    $today = date('Ymd');
  if (!is_admin() AND is_post_type_archive( 'program' ) AND $query->is_main_query()) {
     $query->set('orderby', 'title');
     $query->set('order', 'ASC');
     $query->set('posts_per_page', -1);
  }

  if (!is_admin() AND is_post_type_archive( 'event' ) AND $query->is_main_query()) {
      $query->set('meta_query', 'event_date');
      $query->set('orderby', 'meta_value_num');
      $query->set('order', 'ASC');
      $query->set('meta_query', array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        )
      ));
    }
}
add_action( 'pre_get_posts', 'university_adjust_queries');
//Force Note post to be private
add_filter('wp_insert_post_data', 'makeNotePrivate');
function makeNotePrivate($data){
  if($data['post_type'] == 'note' AND $data['post_status'] != 'trash'){
    $data['post_status'] = 'private';
  }
  return $data;
}
//Redirect subscriber to homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;
  }
}

add_action( 'wp_loaded', 'noSubAdminBar');
function noSubAdminBar(){
  $ourCurrentUSer = wp_get_current_user();
  if (count($ourCurrentUSer->roles) == 1 AND $ourCurrentUSer->roles[0] == 'subscriber') {
  show_admin_bar( false );
  }
}

add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_redirect( home_url() );
  exit();
}

//Customize login screen

add_filter( 'login_headerurl', 'ourHeaderUrl');
function ourHeaderUrl(){
  return esc_url( site_url('/'));
}


add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() {
  return get_bloginfo('name');
}
