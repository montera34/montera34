<?php
// theme setup main function
add_action( 'after_setup_theme', 'montera_theme_setup' );
function montera_theme_setup() {

	// theme global vars
	if (!defined('MONTERA34_BLOGNAME'))
	    define('MONTERA34_BLOGNAME', get_bloginfo('name'));

	if (!defined('MONTERA34_BLOGDESC'))
	    define('MONTERA34_BLOGDESC', get_bloginfo('description','display'));

	if (!defined('MONTERA34_BLOGURL'))
	    define('MONTERA34_BLOGURL', get_bloginfo('url'));

	if (!defined('MONTERA34_BLOGTHEME'))
	    define('MONTERA34_BLOGTHEME', get_bloginfo('template_directory'));

	/* Set up media options: sizes, featured images... */
	add_action( 'init', 'montera34_media_options' );

	/* Add your nav menus function to the 'init' action hook. */
	add_action( 'init', 'montera34_register_menus' );

	/* Load JavaScript files on the 'wp_enqueue_scripts' action hook. */
	add_action( 'wp_enqueue_scripts', 'montera34_load_scripts' );

	// Custom post types
	add_action( 'init', 'montera34_create_post_type', 0 );

	// Custom Taxonomies
	add_action( 'init', 'montera34_build_taxonomies', 0 );

	// Custom Metaboxes Library
	// https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
	// Extra meta boxes in editor
	add_filter( 'cmb_meta_boxes', 'montera34_metaboxes' );
	// Initialize the metabox class
	add_action( 'init', 'montera34_init_metaboxes', 9999 );

	// excerpt support in pages
	add_post_type_support( 'page', 'excerpt' );

	// montera34 shortcodes

} // end montera34 theme setup function

// set up media options
function montera34_media_options() {
	/* Add theme support for post thumbnails (featured images). */
	add_theme_support( 'post-thumbnails', array( 'post','page','montera34_project') );
	set_post_thumbnail_size( 231, 0 ); // default Post Thumbnail dimensions
	/* set up image sizes*/
	update_option('thumbnail_size_w', 231);
	update_option('thumbnail_size_h', 0);
	update_option('medium_size_w', 474);
	update_option('medium_size_h', 0);
	update_option('large_size_w', 717);
	update_option('large_size_h', 0);
} // end set up media options

// register custom menus
function montera34_register_menus() {
        if ( function_exists( 'register_nav_menus' ) ) {
                register_nav_menus(
                array(
                        'sidebar-menu' => 'Menú lateral',
                        'sidebar-icon-menu' => 'Menú lateral con iconos',
                )
                );
        }
} // end register custom menus

// load js scripts to avoid conflicts
function montera34_load_scripts() {
	wp_enqueue_script('jquery');
//	wp_enqueue_script(
//		'bootstrap.min',
//		get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js',
//		array( 'jquery' ),
//		'2.3.2',
//		FALSE
//	);

} // end load js scripts to avoid conflicts

// register post types
function montera34_create_post_type() {
	// Documento custom post type
	register_post_type( 'montera34_project', array(
		'labels' => array(
			'name' => __( 'Projects' ),
			'singular_name' => __( 'Project' ),
			'add_new_item' => __( 'Add a project' ),
			'edit' => __( 'Edit' ),
			'edit_item' => __( 'Edit this project' ),
			'new_item' => __( 'New project' ),
			'view' => __( 'View project' ),
			'view_item' => __( 'View this project' ),
			'search_items' => __( 'Search project' ),
			'not_found' => __( 'No project found' ),
			'not_found_in_trash' => __( 'No projects in trash' ),
			'parent' => __( 'Parent' )
		),
		'has_archive' => true,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		//'menu_icon' => get_template_directory_uri() . '/images/icon-post.type-integrantes.png',
		'hierarchical' => true, // if true this post type will be as pages
		'query_var' => 'project',
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks','thumbnail','page-attributes'),
		'rewrite' => array('slug'=>'project','with_front'=>false),
		'show_ui' => true,
		'show_in_menu' => true,
		'can_export' => true,
		'_builtin' => false,
		'_edit_link' => 'post.php?post=%d',
	));
} // end register post types

// register taxonomies
function montera34_build_taxonomies() {
	register_taxonomy( 'montera34_type', 'montera34_project', array( // type taxonomy
		'hierarchical' => true,
		'label' => __( 'Type' ),
		'name' => __( 'Types' ),
		'query_var' => 'type',
		'rewrite' => array( 'slug' => 'type', 'with_front' => false ),
	) );
} // end register taxonomies

// custom metaboxes
function montera34_metaboxes( $meta_boxes ) {
	$prefix = '_montera34_'; // Prefix for all fields
	// sticky project at home page
	$meta_boxes[] = array(
		'id' => 'project_sticky',
		'title' => 'Show at home page',
		'pages' => array('montera34_project'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'high', // 'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => '',
				'desc' => '',
				'id' => $prefix . 'project_sticky',
				'type' => 'checkbox'
			),
		),
	);
	return $meta_boxes;
} // end Add metaboxes
// Initialize the metabox class
function montera34_init_metaboxes() {
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( 'lib/metabox/init.php' );
	}
} // end Init metaboxes
?>
