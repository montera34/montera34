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
	add_filter( 'image_size_names_choose', 'montera34_custom_sizes' );

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

	// custom loops for each template
	add_filter( 'pre_get_posts', 'montera34_custom_args_for_loops' );

	// montera34 shortcodes

} // end montera34 theme setup function

// set up media options
function montera34_media_options() {
	/* Add theme support for post thumbnails (featured images). */
	add_theme_support( 'post-thumbnails', array( 'post','page','montera34_project','montera34_collabora') );
	set_post_thumbnail_size( 600, 0 ); // default Post Thumbnail dimensions

	// add extra sizes
	add_image_size( 'icon', '32', '32', true );
	add_image_size( 'bigicon', '64', '64', true );
	add_image_size( 'small', '293', '0', false );
	add_image_size( 'extralarge', '1170', '0', false );

	/* set up image sizes*/
	update_option('thumbnail_size_w', 600);
	update_option('thumbnail_size_h', 0);
	update_option('thumbnail_crop', 0);
	update_option('medium_size_w', 585);
	update_option('medium_size_h', 0);
	update_option('large_size_w', 878);
	update_option('large_size_h', 0);
} // end set up media options

function montera34_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'icon' => __('Icon','montera34'),
        'bigicon' => __('Big Icon','montera34'),
        'small' => __('Small','montera34'),
        'extralarge' => __('Extra Large','montera34'),
    ) );
}

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
	//wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css' );
	// wp_enqueue_style( 'bootstrap-theme-css', get_template_directory_uri() . '/css/bootstrap-theme.min.css' );
	//wp_enqueue_style( 'fonts-css', get_template_directory_uri() . '/fonts/stylesheet.css' );

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
	// Project custom post type
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
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks','thumbnail','page-attributes','revisions'),
		'rewrite' => array('slug'=>'project','with_front'=>false),
		'show_ui' => true,
		'show_in_menu' => true,
		'can_export' => true,
		'_builtin' => false,
		'_edit_link' => 'post.php?post=%d',
	));
	// Collaborator custom post type
	register_post_type( 'montera34_collabora', array(
		'labels' => array(
			'name' => __( 'Collaborators' ),
			'singular_name' => __( 'Collaborator' ),
			'add_new_item' => __( 'Add a collaborator' ),
			'edit' => __( 'Edit' ),
			'edit_item' => __( 'Edit this collaborator' ),
			'new_item' => __( 'New collaborator' ),
			'view' => __( 'View collaborator' ),
			'view_item' => __( 'View this collaborator' ),
			'search_items' => __( 'Search collaborator' ),
			'not_found' => __( 'No collaborator found' ),
			'not_found_in_trash' => __( 'No collaborators in trash' ),
			'parent' => __( 'Parent' )
		),
		'has_archive' => true,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		//'menu_icon' => get_template_directory_uri() . '/images/icon-post.type-integrantes.png',
		'hierarchical' => true, // if true this post type will be as pages
		'query_var' => 'collaborator',
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks','thumbnail','page-attributes'),
		'rewrite' => array('slug'=>'collaborator','with_front'=>false),
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
		'show_admin_column' => true
	) );
	register_taxonomy( 'montera34_tech', 'montera34_project', array( // type taxonomy
		'hierarchical' => false,
		'label' => __( 'Technology' ),
		'name' => __( 'Technologies' ),
		'query_var' => 'tech',
		'rewrite' => array( 'slug' => 'tech', 'with_front' => false ),
		'show_admin_column' => true
	) );
} // end register taxonomies

// get all posts from a post type to be used in select or multicheck forms
function montera34_get_list($post_type,$type='multicheck') {
	$posts = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => $post_type,
	));
	if ( $type == 'select' ) {
		$list[] = "none";
	}
	if ( count($posts) > 0 ) {
		foreach ( $posts as $post ) {
			$list[$post->ID] = $post->post_title;
		}
		return $list;
	}
}

// custom metaboxes
function montera34_metaboxes( $meta_boxes ) {
	$prefix = '_montera34_'; // Prefix for all fields

	// get data for select and multicheck fields
	//$itinerarios = quincem_get_list("itinerario");
	$collaborators = montera34_get_list("montera34_collabora");
	$projects = montera34_get_list("montera34_project","select");

	// PROJECT CUSTOM FIELDS
	////

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
	// project card
	$meta_boxes[] = array(
		'id' => 'project_card',
		'title' => 'Project card',
		'pages' => array('montera34_project'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high', // 'high', 'core', 'default' or 'low'
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Project beginning',
				'desc' => 'year',
				'id' => $prefix . 'project_card_date_ini',
				'type' => 'text',
			),
			array(
				'name' => 'Project ending',
				'desc' => 'year',
				'id' => $prefix . 'project_card_date_end',
				'type' => 'text',
			),
			array(
				'name' => 'Project status',
				'desc' => '',
				'id' => $prefix . 'project_card_status',
				'type' => 'wysiwyg',
				'options' => array(
					'textarea_rows' => get_option('default_post_edit_rows', 2),
				)
			),
			array(
				'name' => 'Project URL',
				'desc' => '',
				'id' => $prefix . 'project_card_project_url',
				'type' => 'text_url',
				'protocols' => array( 'http', 'https' )
			),
			array(
				'id' => $prefix . 'project_card_code_repo',
				'type' => 'group',
				'description' => 'Code repo URL',
				'options' => array(
					'group_title' => __( 'Project code', 'montera34' ), // since version 1.1.4, {#} gets replaced by row number
					//'add_button' => __( 'Add Another Project', 'montera34' ),
					//'remove_button' => __( 'Remove Project', 'montera34' ),
				),
				// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
 				'fields' => array(
					array(
						'name' => 'URL text',
						'id'   => 'url_text',
						'type' => 'text',
					),
					array(
						'name' => 'URL',
 						'id'   => 'url',
						'type' => 'text_url',
						'protocols' => array( 'http', 'https' )
					),
				),
			),
			array(
				'id' => $prefix . 'project_card_code_license',
				'type' => 'group',
				'description' => 'Code license URL',
				'options' => array(
					'group_title' => __( 'Code license', 'montera34' ),
					//'add_button' => __( 'Add Another Project', 'montera34' ),
					//'remove_button' => __( 'Remove Project', 'montera34' ),
				),
 				'fields' => array(
					array(
						'name' => 'URL text',
						'id'   => 'url_text',
						'type' => 'text',
					),
					array(
						'name' => 'URL',
 						'id'   => 'url',
						'type' => 'text_url',
						'protocols' => array( 'http', 'https' )
					),
				),
			),
			array(
				'name' => 'Budget',
				'desc' => '',
				'id' => $prefix . 'project_card_money',
				'type' => 'text_money',
				'before' => '€', // Replaces default '$'
			),
			array(
				'name' => 'Client. Name, and URL if any',
				'desc' => '',
				'id' => $prefix . 'project_card_client',
				'type' => 'wysiwyg',
				'options' => array(
					'textarea_rows' => get_option('default_post_edit_rows', 2),
				)
			),
		),
	);
	// Collaborators multicheckbox
	$meta_boxes[] = array(
		'id' => 'montera34_collabora',
		'title' => 'Collaborators',
		'pages' => array('montera34_project'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'default',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Collaborators',
				'id' => $prefix . 'collabora',
				'type' => 'multicheck',
				'options' => $collaborators
			),
		),
	);

	// COLLABORATOR CUSTOM FIELDS
	////
	// collaborator data
	$meta_boxes[] = array(
		'id' => 'collabora_data',
		'title' => 'Collaborator data',
		'pages' => array('montera34_collabora'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high', // 'high', 'core', 'default' or 'low'
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'First name',
				'desc' => '',
				'id' => $prefix . 'collabora_firstname',
				'type' => 'text',
			),
			array(
				'name' => 'Last name',
				'desc' => '',
				'id' => $prefix . 'collabora_lastname',
				'type' => 'text',
			),
			array(
				'name' => 'URL',
				'id'   => $prefix . 'collabora_url',
				'type' => 'text_url',
				'protocols' => array( 'http', 'https'), // Array of allowed protocols
			),
			array(
				'name' => 'Twitter URL',
				'id'   => $prefix . 'collabora_twitter',
				'type' => 'text_url',
				'protocols' => array( 'http', 'https'), // Array of allowed protocols
			),
			array(
				'id' => $prefix . 'collabora_projects',
				'type' => 'group',
				'description' => 'Projects',
				'options' => array(
					'group_title' => __( 'Project {#}', 'montera34' ), // since version 1.1.4, {#} gets replaced by row number
					'add_button' => __( 'Add Another Project', 'montera34' ),
					'remove_button' => __( 'Remove Project', 'montera34' ),
					'sortable' => true, // beta
				),
				// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
 				'fields' => array(
					array(
						'name' => 'Project',
						'id'   => 'project',
						'type' => 'select',
						'options' => $projects,
						'default' => 'none',
						//'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
					),
					array(
						'name' => 'Rol',
						'description' => '',
 						'id'   => 'rol',
						'type' => 'text',
					),
				),
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

// custom args for loops
function montera34_custom_args_for_loops( $query ) {
	if ( is_home() && $query->is_main_query() ) {
		$query->set( 'meta_query', array(
			array(
				'key' => '_montera34_project_sticky',
				'compare' => '=',
				'value' => 'on'
			),
		));
		$query->set( 'post_type',array('montera34_project'));
	}
	return $query;
}

?>
