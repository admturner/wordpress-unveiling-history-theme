<?php
/**
 * Template Name: Participant Splash Page
 * 
 * A custom page template for the Participant Splash Pages.
 *
 * @package WordPress
 * @subpackage ThematicChild_UnveilingHistory
 * @since 0.1.0
 */

    // calling the header.php
    get_header();

    // action hook for placing content above #container
    thematic_abovecontainer();

?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div id="container-splash">
			<h1>Welcome, <?php the_title(); ?></h1>	
		<?php // Check if the user is logged in when we're on cohort pages
		if ( is_user_logged_in() ) : ?>			
			<!-- Start Recent Posts Column-->
			<div id="splash-posts">
				<h2>Recent Posts</h2>
				<?php 
				/**
				 * Display the 3 most recent posts in the participant's Blog
				 *
				 * See the Codex (http://codex.wordpress.org/Template_Tags/get_posts)
				 * for details. To change the number of Featured posts displayed,  
				 * change the numberposts=N number to desired count.
				 *
			 	 * @uses get_posts
			 	 * @since 0.1.0
			 	 */
				global $post;
					$posts = get_posts('numberposts=2'); // Gets the list from the WP database
					foreach( $posts as $post ) : // Loop through the list, and do the following for each post
						setup_postdata($post); // Get the post data to display
						// Here is the HTML markup for each individual post.
						$alt_avatar_url = get_bloginfo( 'stylesheet_directory' ) . '/images/users/' . get_the_author_meta( 'user_login' ) . '.jpg';
						?>
						<div class="recent-post recent-post-<?php the_ID(); ?>">
							<?php echo get_avatar( get_the_author_meta('user_email'), 50, $alt_avatar_url ); /* 50 is the avatar image width in pixels */ ?>
							<h3><a id="post-<?php the_ID(); ?>" <?php post_class(); ?> href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php the_excerpt(); /* auto-wraps in <p> tags */ ?>
							<h4 class="meta">Category: <?php the_category(', '); /* the post's Category */ ?></h4>
						</div>
					<?php endforeach; ?>
					<?php wp_reset_query(); ?>
					<!-- Moved the Edit page and Comments area into the column with recent posts-CR 1.19.11 -->							
					<?php 
					wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'thematic'), "</div>\n", 'number');
					edit_post_link(__('Edit', 'thematic'),'<span class="edit-link">','</span>') ?>

					<?php if ( get_post_custom_values('comments') ) {
						thematic_comments_template(); // Add a key/value of "comments" to enable comments on pages!
					}
					?>
			</div> <!-- End #splash-posts -->
			
			<!-- Start Assignments (categories) Column-->
			<div id="splash-assignments">	
				<h2>Assignment Topics</h2>
				<ul>
				<?php /* The category list, by ID, in descending order (most recent first) */
					wp_list_categories('orderby=id&order=desc&title_li='); 
				?>
				</ul>
			</div><!-- End #splash-assignments -->
		
			<!-- Start Schedule Column --> 
			<div id="right-column">
				<div id="splash-schedule">
					<h2>Schedule Highlights</h2>
					<?php 
					/**
					 * Temporary fix to allow for statically updating 'Schedule Highlights'
					 * 
					 * Until ScholarPress is working with multisite again, the 'Schedule 
					 * Highlights' must be written by hand in the Page content of the
					 * cohort splash Page. 
					 * 
					 * @to-do: Once ScholarPress is working with multisite again, 
					 * return to using the block of code that is commented	out below
					 */		
					the_content();
					/* END temporary fix */ 
					?>
					
				</div><!-- End #splash-schedule -->
				
				<?php 
					$assignment_links = get_post_custom_values('assignment link');
		 			foreach ( $assignment_links as $key => $value ) { ?>
		 				<h4><?php echo $value; ?></h4>
			 		<?php 
		 			} ?>
				
			</div> <!--end right-column-->
		<?php else : // If the user isn't logged in: ?>
			<h2>Please sign in.</h2>
			<?php

			wp_login_form();

		endif; // END "IS USER LOGGED IN" CHECK ?>
	</div><!-- End #container -->
	
	<?php endwhile; ?>

	<?php 	
		// action hook for placing content below #container
		thematic_belowcontainer();	
	?>

	<?php endif; ?>
	
	<?php // calling footer.php
		get_footer();
	?>