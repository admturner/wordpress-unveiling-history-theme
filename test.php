<?php
/**
 * Template Name: Testing Page
 * 
 * A custom page template for testing Things
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
	
	<div id="container-splash">
		
		<?php tah_lessons_list_posts(); ?>
		
	</div><!-- End #container -->
	
	<?php 	
		// action hook for placing content below #container
		thematic_belowcontainer();

	?>
	
	<?php // calling footer.php
		get_footer();
	?>