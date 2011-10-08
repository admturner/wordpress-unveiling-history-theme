<?php
/*
 * Child Theme Functions for Unveiling History.
 *
 * CONTENTS
 * 1. Actions and Filters
 * 2. Universal elements
 * 3. Custom post types
 *
 * @since 0.1.0
 */

// ACTIONS AND FILTERS to modify the default theme
/*
 * Remove Thematic's default primary navigation (#access)
 *
 * @since 0.1.0
 */
function remove_thematic_actions() {
	remove_action('thematic_header','thematic_access',9);
	remove_action('thematic_header','thematic_blogtitle',3);
}
add_action('init', 'remove_thematic_actions');

/*
 * Filter the excerpt's "[...]" more text to display what we want.
 *
 * The default an unlinked [...] to denote more text at the end of an
 * excerpt. The following filter replaces that with a "More..." link.
 * We can also shorted the excerpt length, if desired.
 *
 * @since 0.1.0
 */
function new_excerpt_more( $more ) {
	global $post;
	return ' <span class="more"><a href="' . get_permalink($post->ID) . '">' . 'more...</a></span>';
}
add_filter('excerpt_more', 'new_excerpt_more');

// UNIVERSAL ELEMENTS: primary navigation, featured images (post thumbs),
/*
 * Create our own primary navigation for Unveiling History
 *
 * This uses the Menu functionality to allow for two custom menus
 * in the header div. Create/edit Menus in the WP admin area. See
 * http://codex.wordpress.org/Function_Reference/register_nav_menus
 * for details about creating admin menus on the PHP side.
 *
 * @since 0.1.0
 */
// First create our 2 navigation menus
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
			'uh_public_pages' => 'Primary Public Pages',
			'uh_cohort_pages' => 'Participant Splash Pages'
		)
	);
}
// Now insert them into the template where we removed Thematic's default
function uh_childtheme_access() { ?>
	<div id="access">
		<div class="skip-link"><a href="#content" title="<?php _e('Skip navigation to the content', 'thematic'); ?>"><?php _e('Skip to content', 'thematic'); ?></a></div>
		<?php // Call the navigation menus; parameters and ids/classes can be altered as described in the codex: http://codex.wordpress.org/Function_Reference/wp_nav_menu
			wp_nav_menu( array('menu' => 'Primary Public Pages', 'container_id' => 'public-pages') );
			wp_nav_menu( array('menu' => 'Participant Splash Pages', 'container_id' => 'cohort-pages') );
		?>
	</div><!-- #access -->
<?php 
}
add_action('thematic_header','uh_childtheme_access',8);

/**
 * New title
 * 
 * Replace default title (removed above) with custom title (for href tweaks)
 *
 * @since 0.2.1
 */
function uh_site_title() {
	echo '<div id="blog-title"><span><a href="' . esc_url( get_blogaddress_by_id(1) ) . '" title="Permalink to ' . esc_attr( get_blog_option( 1, 'blogname' ) ) . '" rel="home">' . get_blog_option( 1, 'blogname' ) . '</a></span></div>';
}
add_action('thematic_header','uh_site_title',3);

/*
 * Add support for Featured Images (post thumbnails) and set sizes
 *
 * These can be added on the Edit Post and Edit Page screens in the
 * Admin area. set_post_thumbnail_size defines the default thumbnail
 * size. 1st number = width; 2nd number = height; 'true' tells WP to
 * hard-crop the image to fit the size specified. Delete ', true' to
 * have WP resize the image instead (will retain original aspect ratio).
 * See http://bit.ly/4t2qaq for details.
 *
 * @since 0.1.0
 */
add_theme_support( 'post-thumbnails' ); // Allows us to use featured images on posts and pages
set_post_thumbnail_size( 100, 100, true); // Sets default thumbnail size and tells WP to crop image to size
add_image_size( 'single-post-thumbnail', 400, 9999 ); // Custom size for posting on a Post/Page (can have multiple options). This one is 400px wide and unlimited height.

/**
 * Calls the 960.css stylesheet in the childtheme header
 *
 * @since 0.1.0
 */
function childtheme_create_stylesheet($content) {
 $content .= "\t";
 $content .= "
	<link rel=\"stylesheet\" type=\"text/css\" href=\"";
 $content .= get_bloginfo('stylesheet_directory') . '/';
 $content .= '960.css';
 $content .= "\" />";
 $content .= "\n\n";
 return $content;
}
add_filter('thematic_create_stylesheet', 'childtheme_create_stylesheet');

// create function to call a different layout css file for the cohort splash pages
function childtheme_css() {
	if ( ! is_main_site() ) {
		if ( is_front_page() ) {
		?>
			<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('stylesheet_directory') ?>/3c-r-fixed-primary.css" />
		<?php 
		}
	}
}
add_action('wp_head', 'childtheme_css');

/**
 * Calls our additional scripts on the public pages
 *
 * @since 0.2.0 Using wp_enqueue_script() instead of thematic's workaround
 */
function uh_childtheme_script() {
		wp_register_script( 'colorbox', get_bloginfo('stylesheet_directory') . '/js/jquery/jquery.colorbox-min.js', '', '', true );
		wp_register_script( 'tahscript', get_bloginfo( 'stylesheet_directory' ) . '/js/jquery/tah-script.js', array('jquery', 'colorbox'), '', true );
		wp_enqueue_script( 'tahscript' );
		
		wp_register_style( 'colorbox_style', get_bloginfo('stylesheet_directory') . '/css/colorbox/colorbox.css' );
		wp_enqueue_style( 'colorbox_style' );			
}
add_action('wp_print_styles', 'uh_childtheme_script');

// added by CR 1.20.11 to make shorter excerpts on splash pages, from 
// http://www.transformationpowertools.com/wordpress/automatically-shorten-manual-excerpt
function wp_trim_all_excerpt($text) {
// Creates an excerpt if needed; and shortens the manual excerpt as well
global $post;
  if ( '' == $text ) {
    $text = get_the_content('');
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $text = strip_tags($text); // removed this because it was stripping out a hrefs--cr 1.24.11
  }
// $text = strip_shortcodes( $text ); // optional
$excerpt_length = apply_filters('excerpt_length', 25);
$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
$words = explode(' ', $text, $excerpt_length + 1);
  if (count($words)> $excerpt_length) {
    array_pop($words);
    $text = implode(' ', $words);
    $text = $text . $excerpt_more;
  } else {
    $text = implode(' ', $words);
  }
return $text;
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wp_trim_all_excerpt');
// end of excerpt shortener

/**
 * Filter WordPress login function redirect
 *
 * Filters the redirect default of the WordPress login function
 * to redirect users to their primary blog homepage.
 *
 * @since 0.1.5
 */
function uh_login_redirect($redirect_to, $url_redirect_to = '', $user = null) {
	if( $user->ID ) {
		
		$primary_site = get_active_blog_for_user( $user->ID );
		$user = new WP_User( $user->ID );
		
		if ( ! empty( $user->roles ) ) {	
			if ( ! in_array('administrator', $user->roles) ) {
				return $primary_site->siteurl;
			} else {
				return home_url();
			}
		}
	}
}
add_filter('login_redirect', 'uh_login_redirect', 10, 3);

/**
 * Replace Thematic's default content call to allow for custom content
 *
 * @since 0.1.0
 */
function childtheme_override_content() {
	global $thematic_content_length;
	
		if ( strtolower($thematic_content_length) == 'full' ) {
			$post = get_the_content(more_text());
			$post = apply_filters('the_content', $post);
			$post = str_replace(']]>', ']]&gt;', $post);
		} elseif ( strtolower($thematic_content_length) == 'excerpt') {
			
			// If we're looking at one of the taxonomies
			if ( is_tax('gradelevel') || is_tax('timeperiod' ) || is_page( 'lessons' ) ) {
				// We'll fix the post meta in the next function
				$post = '';
				$post .= tah_lessons_the_overview( 'excerpt' );
				if ( apply_filters( 'thematic_post_thumbs', TRUE) ) {
					$post_title = get_the_title();
					$size = apply_filters( 'thematic_post_thumb_size' , array(100,100) );
					$attr = apply_filters( 'thematic_post_thumb_attr', array('title'	=> 'Permalink to ' . $post_title) );
					if ( has_post_thumbnail() ) {
						$post = '<a class="entry-thumb" href="' . get_permalink() . '" title="Permalink to ' . get_the_title() . '" >' . get_the_post_thumbnail(get_the_ID(), $size, $attr) . '</a>' . $post;
					}
				}
			
			} else {
				$post = '';
				$post .= get_the_excerpt();
				$post = apply_filters('the_excerpt',$post);
				if ( apply_filters( 'thematic_post_thumbs', TRUE) ) {
					$post_title = get_the_title();
					$size = apply_filters( 'thematic_post_thumb_size' , array(100,100) );
					$attr = apply_filters( 'thematic_post_thumb_attr', array('title'	=> 'Permalink to ' . $post_title) );
					if ( has_post_thumbnail() ) {
						$post = '<a class="entry-thumb" href="' . get_permalink() . '" title="Permalink to ' . get_the_title() . '" >' . get_the_post_thumbnail(get_the_ID(), $size, $attr) . '</a>' . $post;
					}
				}
			}
		} elseif ( strtolower($thematic_content_length) == 'none') {
		} else {
			$post = get_the_content(more_text());
			$post = apply_filters('the_content', $post);
			$post = str_replace(']]>', ']]&gt;', $post);
		}
		echo apply_filters('thematic_post', $post);
}

/**
 * Replace Thematic's default metadata function to help out the
 * content call to allow for custom post metadata where needed
 *
 * @since 0.1.0
 */
function childtheme_override_postheader_postmeta() {
	$postmeta = '<div class="entry-meta">';
	if ( is_tax('gradelevel') || is_tax('timeperiod' ) || is_page( 'lessons' ) ) {
		$postmeta .= tah_lessons_the_author();
		// see ../plugins/tah-post-types/tah-post-types-public.php
	} else {
		$postmeta .= thematic_postmeta_authorlink();
	}
	$postmeta .= '<span class="meta-sep meta-sep-entry-date"> | </span>';
	$postmeta .= thematic_postmeta_entrydate();
	$postmeta .= thematic_postmeta_editlink();
	$postmeta .= "</div><!-- .entry-meta -->\n";
	
	return apply_filters( 'childtheme_override_postheader_postmeta', $postmeta ); 
}

/**
 * Replace Thematic's default postmeta function to help out with
 * displaying custom post metadata
 *
 * @since 0.1.0
 */
function childtheme_override_postfooter_postcategory() {
	$postcategory = '<span class="cat-links">';

	if (is_single()) {
		$postcategory .= __('This entry was posted in ', 'thematic') . get_the_category_list(', ');
		if ( is_tax('gradelevel') || is_tax('timeperiod' ) || is_page( 'lessons' ) ) {
			$postcategory .= get_the_term_list( $post->ID, 'gradelevel', ' Grade Level(s): ', ', ' );
			$postcategory .= get_the_term_list( $post->ID, 'timeperiod', ' | Time Period(s): ', ', ' );
		}
		$postcategory .= '</span>';
		
	} elseif ( is_category() && $cats_meow = thematic_cats_meow(', ') ) { /* Returns categories other than the one queried */
		$postcategory .= __('Also posted in ', 'thematic') . $cats_meow;
		if ( is_tax('gradelevel') || is_tax('timeperiod' ) || is_page( 'lessons' ) ) {
			$postcategory .= get_the_term_list( $post->ID, 'gradelevel', ' Grade Level(s): ', ', ' );
		}
		$postcategory .= '</span> <span class="meta-sep meta-sep-tag-links">|</span>';
	} else {
		$postcategory .= __('Posted in ', 'thematic') . get_the_category_list(', ');
		if ( is_tax('gradelevel') || is_tax('timeperiod' ) || is_page( 'lessons' ) ) {
			$postcategory .= get_the_term_list( $post->ID, 'gradelevel', ' Grade Level(s): ', ', ' );
			$postcategory .= get_the_term_list( $post->ID, 'timeperiod', ' | Time Period(s): ', ', ' );
		}
		$postcategory .= '</span> <span class="meta-sep meta-sep-tag-links">|</span>';
	}
	return apply_filters( 'childtheme_override_postfooter_postcategory', $postcategory ); 
}
?>