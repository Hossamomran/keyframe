<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage keyframes
 */

get_header(); ?>

<?php if ( is_home() && ! is_front_page() && ! empty( single_post_title( '', false ) ) ) : ?>
	<header class="page-header alignwide">
		<h1 class="page-title"><?php single_post_title(); ?></h1>
	</header><!-- .page-header -->
<?php endif; ?>
<!-- Popup form !-->
<div id="filter_popup" class="popup-wrapper">
  <div class="popup-content">
    <form id="post-filter" method="post">
      <div>
        <h4 class='filter_posts'> Filter Posts By</h4>
        <label for="category">By Category:</label>
        <?php wp_dropdown_categories( array( 'name' => 'category', 'show_option_all' => 'All' ) ); ?>
      </div>
      <div>
        <label for="from-date">From Month:</label>
        <input type="date" name="from-date" id="from-date">
      </div>
      <div>
        <label for="to-date">ToMonth:</label>
        <input type="date" name="to-date" id="to-date">
      </div>
        <button type="button" id="clear-filter-btn">Clear Filter</button>
        <button type="submit" id="filter-submit">Apply Filter</button>
    </form>
  </div>
</div>
<!-- Popup form !-->

<!-- posts Section !-->
<div class="posts-wrapper">
  <?php
              $postsPerPage = 3;
			  $args = array(
					  'post_type' => 'post',
					  'posts_per_page' => $postsPerPage,
			  );
  
			  $loop = new WP_Query($args);
        $counter = 0; // Set up a counter variable
        $style = "left"; // Set the initial style
			  while ($loop->have_posts()) : $loop->the_post();
        
    // Set the style for this post
    if ($counter % 2 == 0) {
      $style = "left";
    } else {
      $style = "right";
    }

      if ( $counter == 0 ) { // If it's the first post in the loop
        $border_style="border: 1px solid rgb(165, 42, 42);"; //Color the 1st border in the loop
        echo '<div class="welcome-section">
                <h4 class="welcome">Welcome To</h4>  
               <a href="'. esc_url( home_url( "/" ) ).'">  <h3 class="keyframe">Keyframe</h3></a>
                <h5 class="posts_lists">List of posts 2023</h5>
                <button id="filter_button" onclick="togglePopup()">Filter Results</button>
              </div>';
      }else{
        $border_style=" border: 1px solid #ccc;";
      }
      $counter++; 
  ?>
      <div class="post-container"  style="float:<?php echo $style; ?>; <?php echo  $border_style?>;" >
        <div class="post-title"> <?php echo  get_the_title() ?></div>
        <div class="post-date"><?php echo  get_the_date() ?> </div>
        <div class="post-category"> <?php echo  get_the_category_list( ', ' ) ?></div>
        <div class="post-description"><?php echo wp_trim_excerpt( get_the_excerpt() ); ?></div>
        <div class="see-more">
          <a href="<?php the_permalink(); ?>">See More</a>
        </div>
      </div>
  <?php 
  endwhile;
  wp_reset_postdata();
  ?>
</div>

<button id="more_posts">Load More</button>

<script>
 jQuery(document).ready(function ($){
	var post_per_page = 3; 
	var pageNumber = 1;

	function load_posts(){
	    pageNumber++;
	    var data = '&pageNumber=' + pageNumber + '&post_per_page=' + post_per_page + '&action=more_post_ajax';
	    $.ajax({
	        type: "POST",
	        dataType: "html",
			url:  '<?php echo esc_url( admin_url('admin-ajax.php') );?>',
	        data: data,
	        success: function(data){
	            var $data = $(data);
	            if($data.length){
	                console.log($data);
	                $(".posts-wrapper").append($data);
	                $("#more_posts").attr("disabled",false);
	            } else{
	                $("#more_posts").attr("disabled",true);
                  jQuery('#more_posts').css({"display":"none"});

	            }
	        },
	        error : function(jqXHR, textStatus, errorThrown) {
	            $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
	        }

	    });
	    return false;
	}

	$("#more_posts").on("click",function(){ // When btn is pressed.
	    $("#more_posts").attr("disabled",true); // Disable the button, temp.
	    load_posts();
	});

});

jQuery(document).ready(function($){
  $(document).mouseup(function(e) {
    var popupContent = $(".popup-content");
    if (!popupContent.is(e.target) && popupContent.has(e.target).length === 0) {
      $("#filter_popup").fadeOut();
    }
  });

  $('#post-filter').on('submit', function(event){
    event.preventDefault(); 

    // get the form data
    var formData = $(this).serialize();

    // send the AJAX request
    $.ajax({
      url: '<?php echo esc_url( admin_url('admin-ajax.php') );?>',
      type: 'POST',
      data: formData + '&action=filter_posts',
      success: function(response){
        // replace the posts wrapper with the filtered posts
        $('.posts-wrapper').html(response);
      }
    });
  });
});

</script>
<?php
get_footer();
