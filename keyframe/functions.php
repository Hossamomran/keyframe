<?php 

function enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/includes/css/style.css', false, '1.0', 'all' ); // Inside a parent theme
    wp_enqueue_script('theme-script',get_stylesheet_directory_uri().'/includes/js/script.js');
}
add_action('wp_enqueue_scripts', 'enqueue_scripts');

//Ajax callback for load more posts 
add_action('wp_ajax_more_post_ajax', 'more_post_ajax_callback');
add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax_callback');
function more_post_ajax_callback(){
    $post_per_page = $_POST["post_per_page"];
    $pageNumber = $_POST["pageNumber"];
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $post_per_page,
        'paged' => $pageNumber
    );
    $loop = new WP_Query($args);
    
    while ($loop->have_posts()) : $loop->the_post();
        $output = '<div class="post-container" >';
        $output .= '<div class="post-title">' . get_the_title() . '</div>';
        $output .= '<div class="post-date">' . get_the_date() . '</div>';
        $output .= '<div class="post-category">' . get_the_category_list( ', ' ) . '</div>';
        $output .= '<div class="post-description">'. wp_trim_excerpt( get_the_excerpt() ).'</div>';
        $output .= '<div class="see-more"><a href="' . get_the_permalink() . '">See More</a></div>';
        $output .= '</div>';
        echo $output;
    endwhile;

    wp_reset_postdata();
    wp_die();
}

//Ajax callback for filter posts
add_action( 'wp_ajax_nopriv_filter_posts', 'filter_posts' );
add_action( 'wp_ajax_filter_posts', 'filter_posts' );

function filter_posts() {
  $args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
  );

  if (isset($_POST['category']) && $_POST['category'] != '') {
    $args['cat'] = intval($_POST['category']);
  }

  if (isset($_POST['from-date']) && $_POST['from-date'] != '') {
    $args['date_query']['after'] = $_POST['from-date'];
  }

  if (isset($_POST['to-date']) && $_POST['to-date'] != '') {
    $args['date_query']['before'] = $_POST['to-date'];
  }

  $query = new WP_Query($args);

  //echo the welcome section
  echo '<div class="welcome-section">
  <h4 class="welcome">Welcome To</h4>
  <a href="'. esc_url( home_url( "/" ) ).'">  <h3 class="keyframe">Keyframe</h3></a>
  <h5 class="posts_lists">List of posts 2023</h5>
  <button id="filter_button" onclick="togglePopup()">Filter Results</button>
  </div>';

  if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
   <div class="post-container"  style="float:<?php echo $style; ?>; <?php echo  $border_style?>;" >
        <div class="post-title"> <?php echo  get_the_title() ?></div>
        <div class="post-date"><?php echo  get_the_date() ?> </div>
        <div class="post-category"> <?php echo  get_the_category_list( ', ' ) ?></div>
        <div class="post-description"><?php echo wp_trim_excerpt( get_the_excerpt() ); ?></div>
        <div class="see-more">
          <a href="<?php the_permalink(); ?>">See More</a>
        </div>
      </div>
  <?php endwhile; endif;
  
  wp_reset_postdata();
  die();
}

//Remove [..] from excerpt
function custom_excerpt_more( $more ) {
  return '';
}
add_filter( 'excerpt_more', 'custom_excerpt_more', 11 );

//// Change the number of words in excerpt
add_filter( 'excerpt_length', function($length) {
  return 20; 
});