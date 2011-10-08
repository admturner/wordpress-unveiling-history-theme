<?php

    // calling the header.php
    get_header();

    // action hook for placing content above #container
    thematic_abovecontainer();

?>

	<div id="container">
		<div id="content">

            <?php 
            	// calling the widget area 'page-top'
            	get_sidebar('page-top');
	            
	            the_post()
            ?>
            
            
            <?php 
			/**
			 * Start of conditional tests to see what type of
			 * page we are looking at, and display content 
			 * accordingly.
			 */
			
			// START HOME PAGE. These scripts output the content of the home page.
			if ( is_front_page() && !is_page_template('page-cohort-splash.php') ) : 
				
				// If we're looking at the main Home page ?>

				<div id="primary-wrap"> 
				<!-- =========#primary-wrap encloses the featured posts and the calendar 
				so it can get its own background, height, etc.======== -->           	
				<!-- ====== Featured DIV wrap here two featured posts go in here, 
				inside entry-content====== -->
	            	<div id="featured">
												
						<?php
						/**
						 * Randomly display two posts from the "Featured" category.
						 *
						 * See the Codex (http://codex.wordpress.org/Template_Tags/get_posts)
						 * for details. Change to: get_posts('numberposts=2&cat=5'); to 
						 * eliminate the random selection and simply order by most recent.
						 * Also, to change the number of Featured posts displayed, change 
						 * the numberposts=2 number to desired count.
						 *
						 * @uses get_posts
						 * @since 0.1.0
						 */
						global $post;
						$args = array(
								'post_type' => array( 'lessons', 'post' ),
								'numberposts' => 2,
								'cat' => 3,
								'orderby' => rand 
							);
						$featuredposts = get_posts( $args ); // Gets the list from the WP database
						foreach( $featuredposts as $post ) : // Loop through the list, and do the following for each post
							setup_postdata($post); // Get the post data to display

							// The following is what is actually output for each Featured Post
							?>
							<div id="post-<?php the_ID(); ?>" class="<?php thematic_post_class() ?>"> 
								<div class="entry-content"><!-- -->
									<?php if ( has_post_thumbnail() ) {
										the_post_thumbnail(); // This is the image thumb, set this on the Edit Post page (default size; set in functions.php)
									} else { // If the Post doesn't specify a Featured Image, then display the following img (chosen arbitrarily) ?>
										<img width="100" height="100" src="<?php bloginfo('stylesheet_directory'); ?>/images/header-logo_127x127.gif" class="attachment-post-thumbnail wp-post-image" alt="" />
									<?php } ?>
									<h2><a id="post-<?php the_ID(); ?>" <?php post_class(); ?> href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<?php the_excerpt(); // auto-wraps in <p> tags ?>
								</div><!-- end .entry content -->				
							</div> <!-- end #post -->

						<?php // Close out the Featured Posts script
						endforeach; ?>
						<?php wp_reset_query(); ?>

					</div><!--end #featured-->


	<!-- ===== END Featured DIV wrap here ====== -->

					<?php
					/**
					 * The "Coming Up" section pulls from the My Calendar plugin
					 *
					 * Display the widget area 'page-bottom' on the home page 
					 * for the My Calendar schedule. Edit the events using the 
					 * My Calendar plugin. Change the display of the upcoming
					 * events section of the home page in Widgets (widget-area
					 * Page Bottom). For help on formatting, see the My Calendar->
					 * Help page in the admin area.
					 *
					 * @since 0.1.0
					 * @uses my-calendar
					 */
					?>
					<div id="calendar"><!-- ========calendar div will be to right of featured area========== -->

						<?php the_content(); ?>

						<div id="button-wrap">
							<a href="<?php bloginfo('url'); ?>/apply/">
							<div class="cta-button hide-text">Apply/Learn More</div>
							<!--end .cta-button--></a>
						</div><!--end #button-wrap-->										
					</div><!--end #calendar-->
				
					<?php 
					/**
					 * The 4 public page links and blurbs section pulls from Custom Fields
					 *
					 * The following section titles each section and then pulls in
					 * the content of the specified Custom Field from the home page.
					 * Edit these in the Home page's WP Admin Edit Page interface.
					 *
					 * @since 0.1.0
					 */
					?>
				</div><!--end #primary-wrap-->
				<div class="clearer"></div>
				
				<div id="sections">

					<div class="one-fourth">
						<h2 id="PSAs" class="hide-text">Primary Source Activities</h2> <!-- ===these get IDs to provide hook to put in background icons=== -->
						<p><?php echo get_post_meta($post->ID, 'source-activities', true); ?></p>
					</div>
					<div class="one-fourth">
						<h2 id="Pods" class="hide-text">Podcasts</h2>
						<p><?php echo get_post_meta($post->ID, 'podcasts', true); ?></p>
					</div>
					<div class="one-fourth">
						<h2 id="Lessons" class="hide-text">Lessons</h2>
						<p><?php echo get_post_meta($post->ID, 'lessons', true); ?></p>
					</div>
					<div class="one-fourth last">
						<h2 id="Resources" class="hide-text">Resources</h2>
						<p><?php echo get_post_meta($post->ID, 'resources', true); ?></p>
					</div>
					<?php

					wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'thematic'), "</div>\n", 'number');

					edit_post_link(__('Edit', 'thematic'),'<div class="edit-link">','</div>') ?>
				</div><!--end #sections-->
        							
				<?php
       			
       		// END HOME PAGE CONTENT
       		
       		// START ABOUT PAGE CONENT
			elseif ( is_page('about-unveilinghistory') || is_page('about-the-program') ) : 

				if ( is_page('about-unveilinghistory') ) : 
					// If we're looking at the public About page ?>
					<?php // This outputs the page title (and metadata where applicable)
						thematic_postheader(); 
					?>

					<div id="post-<?php the_ID(); ?>" class="<?php thematic_post_class() ?>">
											
						<div class="entry-content">

							<?php the_content(); ?>
						
							<?php
							
							wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'thematic'), "</div>\n", 'number');
							
							edit_post_link(__('Edit', 'thematic'),'<span class="edit-link">','</span>') ?>
	
						</div><!-- .entry content -->
					</div><!-- .post -->
				<?php endif; ?>
				
				<?php if ( is_page('about') ) :

					// If we're looking at a cohort About page ?>

							<?php // This outputs the page title (and metadata where applicable)
								thematic_postheader(); 
							?>

					<div id="post-<?php the_ID(); ?>" class="<?php thematic_post_class() ?>">
						
						<div class="entry-content">
							
							<?php 
							/**
							 * Left column: Participant Blurbs
							 *
							 * These are pulled dynamically from each user's WordPress
							 * profile. Filtered by user capability (role). Either 
							 * participants can create them, or project staff can.
							 * See http://codex.wordpress.org/Template_Tags/wp_list_authors
							 *
							 * @todo Write custom function to list users by capability to display here.
							 *
							 * @since 0.1.0
							 * @uses wp_list_authors()
							 */
							_e('<h3>Participants</h3>'); ?>
								<ul>
									<?php $participants = $wpdb->get_results("SELECT * FROM $wpdb->users ORDER BY display_name");
									foreach ($participants as $participant) : ?>
										<?php if ( !current_user_can('delete_others_posts') ) { ?>
										<li><a href="<?php bloginfo('url');?>/author/<?php echo $participant->user_nicename; ?>/" title="Posts by <?php echo $participant->display_name; ?>"><?php echo $participant->user_nicename; ?></a></li>
										<?php } ?>
									<?php endforeach; ?>
								</ul>
							<?php
							/**
							 * Right column: Project Staff Blurbs
							 *
							 * These are pulled dynamically from each staff members's 
							 * WordPress profile. Filtered by user capability (role).
							 * See http://codex.wordpress.org/Template_Tags/wp_list_authors
							 *
							 * @todo Write custom function to list users by capability to display here.
							 *
							 * @since 0.1.0
							 * @uses wp_list_authors()
							 */
							_e('<h3>Project Staff</h3>'); ?>
								<ul>
								</ul>
						
							<?php
							
							wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'thematic'), "</div>\n", 'number');
							
							edit_post_link(__('Edit', 'thematic'),'<span class="edit-link">','</span>') ?>
	
						</div><!-- .entry content -->
					</div><!-- .post -->
				<?php endif; ?>
			<?php // END ABOUT PAGE CONTENT
			
			// START LESSONS PAGE CONENT
			elseif ( is_page( 'lessons' ) || is_page( 'online-resources' ) || is_page( 'print-resources' ) ) : 
				// This outputs the page title (and metadata where applicable)
				thematic_postheader(); 
				?>

				<div id="post-<?php the_ID(); ?>" class="<?php thematic_post_class() ?>">
				
					<?php the_content(); ?>
				
					<?php
					
					wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'thematic'), "</div>\n", 'number');
					
					edit_post_link(__('Edit', 'thematic'),'<span class="edit-link">','</span>') ?>

				</div><!-- .post --> 
			
			<?php // START DEFAULT PAGE
			else : 
				// Nothing special, just display the regular content ?>
				<div id="post-<?php the_ID(); ?>" class="<?php thematic_post_class() ?>">

					<?php // This outputs the page title (and metadata where applicable)
						thematic_postheader(); 
					?>
					
					<div class="entry-content">
						<?php the_content(); ?>
					
						<?php
						
						wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'thematic'), "</div>\n", 'number');
						
						edit_post_link(__('Edit', 'thematic'),'<span class="edit-link">','</span>') ?>

					</div><!-- .entry content -->
				</div><!-- .post -->
					
			<?php // End of the Page-checking, continue as usual
			endif; ?>

        <?php
        
        if ( get_post_custom_values('comments') ) 
            thematic_comments_template(); // Add a key/value of "comments" to enable comments on pages!       
        
        ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php 

    // action hook for placing content below #container
    thematic_belowcontainer();

    // Calling the standard sidebar, as long as we're not on the Home page
    if ( !is_front_page() && !is_page('about') && !is_page('about-the-program') )
    	thematic_sidebar();
    
    // calling footer.php
    get_footer();

?>