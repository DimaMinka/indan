<?php
/*
Plugin Name: Portfolio Post Type
Plugin URI: http://www.megathe.me
Description: Enables a portfolio post type and taxonomies.
Version: 1.0
Author: MegaTheme
Author URI: http://www.megathe.me
License: GPL-2.0+
*/

/**
 * Registering a post type called "Portfolios".
 */
function create_portfolio_type() {
	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name' => __( 'Portfolio', 'mega' ),
				'singular_name' => __( 'Portfolio', 'mega' ),
				'add_new' => _x( 'Add New', 'portfolio', 'mega' ),
				'add_new_item' => __( 'Add New Portfolio', 'mega' ),
				'edit_item' => __( 'Edit Portfolio', 'mega' ),
				'new_item' => __( 'New Portfolio', 'mega' ),
				'all_items' => __( 'All Portfolios', 'mega' ),
				'view_item' => __( 'View Portfolio', 'mega' ),
				'search_items' => __( 'Search Portfolio', 'mega' ),
				'not_found' =>  __( 'No portfolios found', 'mega' ),
				'not_found_in_trash' => __( 'No portfolios found in Trash', 'mega' )
			),
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'portfolio', 'with_front' => false ),
			'capability_type' => 'post',
			'has_archive' => false,
			'public' => true,
			'hierarchical' => false,
			'menu_position' => 5,
			'exclude_from_search' => false,
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' )
		)
	);
}
add_action( 'init', 'create_portfolio_type' );

/**
* Displays the portfolio icon in the glance view for version 3.8 and up.
*/
function add_icons() {

	if ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) {
		?>
		<style>
			#menu-posts-portfolio .menu-icon-post div.wp-menu-image:before {
				content: "\f322" !important;
			}
		</style>
	<?php }
}
add_action( 'admin_head', 'add_icons' );

// create taxonomy, categories for the post type "Portfolios"
function create_portfolio_taxonomies() {
	$labels = array(
		'name' => __( 'Portfolio Categories', 'mega' ),
		'singular_name' => __( 'Category', 'mega' ),
		'all_items' => __( 'All', 'mega' ),
	); 
	register_taxonomy( 'portfolio-category', array( 'portfolio' ), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_tagcloud' => false,
		'show_in_nav_menus' => true,
		'query_var' => true
	) );
}
add_action( 'init', 'create_portfolio_taxonomies' );

// add filter to ensure the text Portfolio, or portfolio, is displayed when user updates a portfolio 
function portfolio_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['portfolio'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Portfolio updated. <a href="%s">View portfolio</a>', 'mega'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.', 'mega'),
    3 => __('Custom field deleted.', 'mega'),
    4 => __('Portfolio updated.', 'mega'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Portfolio restored to revision from %s', 'mega'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Portfolio published. <a href="%s">View portfolio</a>', 'mega'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Portfolio saved.', 'mega'),
    8 => sprintf( __('Portfolio submitted. <a target="_blank" href="%s">Preview portfolio</a>', 'mega'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Portfolio scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview portfolio</a>', 'mega'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i', 'mega' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Portfolio draft updated. <a target="_blank" href="%s">Preview portfolio</a>', 'mega'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}
add_filter( 'post_updated_messages', 'portfolio_updated_messages' );

// display contextual help for Portfolio

function portfolio_add_help_text( $contextual_help, $screen_id, $screen ) {
  // $contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
  if ( 'portfolio' == $screen->id ) {
    $customize_display = '<p>' . __('The title field and the big Portfolio Editing Area are fixed in place, but you can reposition all the other boxes using drag and drop, and can minimize or expand them by clicking the title bar of each box. Use the Screen Options tab to unhide more boxes (Excerpt, Send Trackbacks, Custom Fields, Discussion, Slug, Author) or to choose a 1- or 2-column layout for this screen.', 'mega') . '</p>';

	get_current_screen()->add_help_tab( array(
		'id'      => 'customize-display',
		'title'   => __('Customizing This Display', 'mega'),
		'content' => $customize_display,
	) );

	$title_and_editor  = '<p>' . __('<strong>Title</strong> - Enter a title for your portfolio. After you enter a title, you&#8217;ll see the permalink below, which you can edit.', 'mega') . '</p>';
	$title_and_editor .= '<p>' . __('<strong>Portfolio editor</strong> - Enter the text for your portfolio. There are two modes of editing: Visual and HTML. Choose the mode by clicking on the appropriate tab. Visual mode gives you a WYSIWYG editor. Click the last icon in the row to get a second row of controls. The HTML mode allows you to enter raw HTML along with your portfolio text. You can insert media files by clicking the icons above the portfolio editor and following the directions. You can go to the distraction-free writing screen via the Fullscreen icon in Visual mode (second to last in the top row) or the Fullscreen button in HTML mode (last in the row). Once there, you can make buttons visible by hovering over the top area. Exit Fullscreen back to the regular portfolio editor.', 'mega') . '</p>';

	get_current_screen()->add_help_tab( array(
		'id'      => 'title-portfolio-editor',
		'title'   => __('Title and Portfolio Editor', 'mega'),
		'content' => $title_and_editor,
	) );

	$publish_box = '<p>' . __('<strong>Publish</strong> - You can set the terms of publishing your portfolio in the Publish box. For Status, Visibility, and Publish (immediately), click on the Edit link to reveal more options. Visibility includes options for password-protecting a portfolio or making it stay at the top of your blog indefinitely (sticky). Publish (immediately) allows you to set a future or past date and time, so you can schedule a portfolio to be published in the future or backdate a portfolio.', 'mega') . '</p>';

	if ( current_theme_supports( 'post-thumbnails' ) && post_type_supports( 'post', 'thumbnail' ) ) {
		$publish_box .= '<p>' . __('<strong>Featured Image</strong> - This allows you to associate an image with your portfolio without inserting it. This is usually useful only if your theme makes use of the featured image as a portfolio thumbnail on the home page, a custom header, etc.', 'mega') . '</p>';
	}

	get_current_screen()->add_help_tab( array(
		'id'      => 'publish-box',
		'title'   => __('Publish Box', 'mega'),
		'content' => $publish_box,
	) );

	$discussion_settings  = '<p>' . __('<strong>Send Trackbacks</strong> - Trackbacks are a way to notify legacy blog systems that you&#8217;ve linked to them. Enter the URL(s) you want to send trackbacks. If you link to other WordPress sites they&#8217;ll be notified automatically using pingbacks, and this field is unnecessary.', 'mega') . '</p>';
	$discussion_settings .= '<p>' . __('<strong>Discussion</strong> - You can turn comments and pings on or off, and if there are comments on the portfolio, you can see them here and moderate them.', 'mega') . '</p>';

	get_current_screen()->add_help_tab( array(
		'id'      => 'discussion-settings',
		'title'   => __('Discussion Settings', 'mega'),
		'content' => $discussion_settings,
	) );

	get_current_screen()->set_help_sidebar(
			'<p>' . sprintf(__('You can also create portfolio with the <a href="%s">Press This bookmarklet</a>.'), 'mega') . '</p>' .
			'<p><strong>' . __('For more information:', 'mega') . '</strong></p>' .
			'<p>' . __('<a href="http://codex.wordpress.org/Posts_Add_New_Screen" target="_blank">Documentation on Writing and Editing Posts</a>', 'mega') . '</p>' .
			'<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'mega') . '</p>'
	);
  }
  return $contextual_help;
}
add_action( 'contextual_help', 'portfolio_add_help_text', 10, 3 );
