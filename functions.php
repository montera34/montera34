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

	//  Add responsive container to embed
	add_filter( 'embed_oembed_html', 'montera34_embed_html', 10, 3 );
	add_filter( 'video_embed_html', 'montera34_embed_html' ); // Jetpack

	/* Filter caption shortcode */
	add_filter( 'img_caption_shortcode', 'montera34_img_caption_shortcode_filter', 10, 3 );

	/* Add your nav menus function to the 'init' action hook. */
	add_action( 'init', 'montera34_register_menus' );

	/* user profile extra field: custom avatar */
	add_filter('user_contactmethods', 'montera34_user_extra_fields' );

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

	// Filter body_class function
	add_filter('body_class', 'montera34_body_classes');

	// load language files
	load_theme_textdomain('montera34', get_template_directory(). '/languages');

	// polylang translation plugin functions
	add_filter('pll_copy_post_metas', 'montera34_translate_copy_post_metas');
	add_filter('pll_translation_url', 'montera34_author_translation_url', 10, 2);

	// enable post thumbnails in all post types
	add_theme_support( 'post-thumbnails' );

} // end montera34 theme setup function

//  Add responsive container to embed
function montera34_embed_html( $html ) {
    return '<div class="video-container">' . $html . '</div>';
}
 
// set up media options
function montera34_media_options() {
	/* Add theme support for post thumbnails (featured images). */
	add_theme_support( 'post-thumbnails', array( 'post','page','montera34_project','montera34_collabora') );
	set_post_thumbnail_size( 600, 0 ); // default Post Thumbnail dimensions

	// add extra sizes
	add_image_size( 'icon', '32', '32', true );
	add_image_size( 'bigicon', '64', '64', true );
	add_image_size( 'small', '263', '0', false );
	add_image_size( 'newsletter', '500', '0', false );
	add_image_size( 'extralarge', '1200', '0', false );

	/* set up image sizes*/
	update_option('thumbnail_size_w', 628);
	update_option('thumbnail_size_h', 0);
	update_option('thumbnail_crop', 0);
	update_option('medium_size_w', 555);
	update_option('medium_size_h', 0);
	update_option('large_size_w', 848);
	update_option('large_size_h', 0);
} // end set up media options

function montera34_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'icon' => __('Icon','montera34'),
        'bigicon' => __('Big Icon','montera34'),
        'small' => __('Small','montera34'),
        'newsletter' => __('Newsletter','montera34'),
        'extralarge' => __('Extra Large','montera34'),
    ) );
}

/**
 * Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
 * by JoostKiens
 * https://gist.github.com/JoostKiens/4477366
 * modified to remove width style from output to make it more responsive.
 * 
 * @param  string $val     Empty
 * @param  array  $attr    Shortcode attributes
 * @param  string $content Shortcode content
 * @return string          Shortcode output
 */
function montera34_img_caption_shortcode_filter($val, $attr, $content = null)
{
	extract(shortcode_atts(array(
		'id'      => '',
		'align'   => 'aligncenter',
		'width'   => '',
		'caption' => ''
	), $attr));
	
	// No caption, no dice... But why width? 
	if ( 1 > (int) $width || empty($caption) )
		return $val;
 
	if ( $id )
		$id = esc_attr( $id );
     
	// Add itemprop="contentURL" to image - Ugly hack
	$content = str_replace('<img', '<img itemprop="contentURL"', $content);

	return '<figure id="' . $id . '" aria-describedby="figcaption_' . $id . '" class="wp-caption ' . esc_attr($align) . '" itemscope itemtype="http://schema.org/ImageObject">' . do_shortcode( $content ) . '<figcaption id="figcaption_'. $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption></figure>';
}

// user profile extra field: custom avatar
function montera34_user_extra_fields($profile_fields) {
	$profile_fields['twitter'] = __('Twitter URL','montera34');
	$profile_fields['custom_avatar'] = __('Custom avatar','montera34');
	return $profile_fields;
}

// register custom menus
function montera34_register_menus() {
        if ( function_exists( 'register_nav_menus' ) ) {
                register_nav_menus(
                array(
                        'sidebar-menu' => __('Sidebar menu','montera34'),
                        'footer-menu' => __('Footer menu','montera34'),
                )
                );
        }
} // end register custom menus

// load js scripts to avoid conflicts
function montera34_load_scripts() {
	//wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css' );
	// wp_enqueue_style( 'bootstrap-theme-css', get_template_directory_uri() . '/css/bootstrap-theme.min.css' );
//	wp_enqueue_style( 'glyphs-css', get_template_directory_uri() . '/glyphs.css' );
	wp_enqueue_style(
		'fa-css',
		get_template_directory_uri() . '/font-awesome/css/font-awesome.min.css'
	);

//	wp_enqueue_script('jquery');
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
			'name' => __( 'Projects','montera34' ),
			'singular_name' => __( 'Project','montera34' ),
			'add_new_item' => __( 'Add a project','montera34' ),
			'edit' => __( 'Edit','montera34' ),
			'edit_item' => __( 'Edit this project','montera34' ),
			'new_item' => __( 'New project','montera34' ),
			'view' => __( 'View project','montera34' ),
			'view_item' => __( 'View this project','montera34' ),
			'search_items' => __( 'Search project','montera34' ),
			'not_found' => __( 'No project found','montera34' ),
			'not_found_in_trash' => __( 'No projects in trash','montera34' ),
			'parent' => __( 'Parent','montera34' )
		),
		'has_archive' => true,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		//'menu_icon' => get_template_directory_uri() . '/images/icon-post.type-integrantes.png',
		'hierarchical' => true, // if true this post type will be as pages
		'query_var' => 'project',
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks','thumbnail','page-attributes','revisions','custom-fields'),
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
			'name' => __( 'Collaborators','montera34' ),
			'singular_name' => __( 'Collaborator','montera34' ),
			'add_new_item' => __( 'Add a collaborator','montera34' ),
			'edit' => __( 'Edit','montera34' ),
			'edit_item' => __( 'Edit this collaborator','montera34' ),
			'new_item' => __( 'New collaborator','montera34' ),
			'view' => __( 'View collaborator','montera34' ),
			'view_item' => __( 'View this collaborator','montera34' ),
			'search_items' => __( 'Search collaborator','montera34' ),
			'not_found' => __( 'No collaborator found','montera34' ),
			'not_found_in_trash' => __( 'No collaborators in trash','montera34' ),
			'parent' => __( 'Parent','montera34' )
		),
		'has_archive' => true,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		//'menu_icon' => get_template_directory_uri() . '/images/icon-post.type-integrantes.png',
		'hierarchical' => true, // if true this post type will be as pages
		'query_var' => 'collaborator',
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks','thumbnail','page-attributes','custom_fields'),
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
		'label' => __( 'Type','montera34' ),
		'name' => __( 'Types','montera34' ),
		'query_var' => 'type',
		'rewrite' => array( 'slug' => 'type', 'with_front' => false ),
		'show_admin_column' => true
	) );
	register_taxonomy( 'montera34_tech', 'montera34_project', array( // type taxonomy
		'hierarchical' => false,
		'label' => __( 'Technology','montera34' ),
		'name' => __( 'Technologies','montera34' ),
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
		'post_status' => array( 'publish', 'private' ),
		'orderby' => 'title',
		'order' => 'ASC'
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
	$collaborators = montera34_get_list("montera34_collabora");
	$projects = montera34_get_list("montera34_project","select");

	// PROJECT CUSTOM FIELDS
	////

	// sticky project at home page
	$meta_boxes[] = array(
		'id' => 'project_sticky',
		'title' => __( 'Show at home page','montera34' ),
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
		'title' => __( 'Project card','montera34' ),
		'pages' => array('montera34_project'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high', // 'high', 'core', 'default' or 'low'
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __( 'Project beginning','montera34' ),
				'desc' => 'year',
				'id' => $prefix . 'project_card_date_ini',
				'type' => 'text',
			),
			array(
				'name' => __( 'Project ending','montera34' ),
				'desc' => 'year',
				'id' => $prefix . 'project_card_date_end',
				'type' => 'text',
			),
			array(
				'name' => __( 'Project status','montera34' ),
				'desc' => '',
				'id' => $prefix . 'project_card_status',
				'type' => 'wysiwyg',
				'options' => array(
					'textarea_rows' => get_option('default_post_edit_rows', 2),
				)
			),
			array(
				'name' => __( 'Project URL','montera34' ),
				'desc' => '',
				'id' => $prefix . 'project_card_project_url',
				'type' => 'text_url',
				'protocols' => array( 'http', 'https' )
			),
			array(
				'id' => $prefix . 'project_card_code_repo',
				'type' => 'group',
				'description' => __( 'Code repo URL','montera34' ),
				'options' => array(
					'group_title' => __( 'Project code', 'montera34' ), // since version 1.1.4, {#} gets replaced by row number
					//'add_button' => __( 'Add Another Project', 'montera34' ),
					//'remove_button' => __( 'Remove Project', 'montera34' ),
				),
				// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
 				'fields' => array(
					array(
						'name' => __( 'URL text','montera34' ),
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
				'description' => __( 'Code license URL','montera34' ),
				'options' => array(
					'group_title' => __( 'Code license', 'montera34' ),
					//'add_button' => __( 'Add Another Project', 'montera34' ),
					//'remove_button' => __( 'Remove Project', 'montera34' ),
				),
 				'fields' => array(
					array(
						'name' => __( 'URL text','montera34' ),
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
				'name' => __( 'Budget','montera34' ),
				'desc' => '',
				'id' => $prefix . 'project_card_money',
				'type' => 'text_money',
				'before' => '€', // Replaces default '$'
			),
			array(
				'name' => __( 'Client. Name, and URL if any','montera34' ),
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
		'title' => __( 'Collaborators','montera34' ),
		'pages' => array('montera34_project'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'default',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => __( 'Collaborators','montera34' ),
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
		'title' => __( 'Collaborator data','montera34' ),
		'pages' => array('montera34_collabora'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high', // 'high', 'core', 'default' or 'low'
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __( 'First name','montera34' ),
				'desc' => '',
				'id' => $prefix . 'collabora_firstname',
				'type' => 'text',
			),
			array(
				'name' => __( 'Last name','montera34' ),
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
				'name' => __( 'Twitter URL','montera34' ),
				'id'   => $prefix . 'collabora_twitter',
				'type' => 'text_url',
				'protocols' => array( 'http', 'https'), // Array of allowed protocols
			),
			array(
				'id' => $prefix . 'collabora_projects',
				'type' => 'group',
				'description' => __( 'Projects','montera34' ),
				'options' => array(
					'group_title' => __( 'Project {#}', 'montera34' ), // since version 1.1.4, {#} gets replaced by row number
					'add_button' => __( 'Add Another Project', 'montera34' ),
					'remove_button' => __( 'Remove Project', 'montera34' ),
					'sortable' => true, // beta
				),
				// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
 				'fields' => array(
					array(
						'name' => __( 'Project','montera34' ),
						'id'   => 'project',
						'type' => 'select',
						'options' => $projects,
						'default' => 'none',
						//'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
					),
					array(
						'name' => __( 'Rol','montera34' ),
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

// count how many projects a collaborator has and add the number as a CF
add_action('wp_insert_post', 'montera34_cf_count_collabora_projects');
function montera34_cf_count_collabora_projects() {

	global $post;
	if ( $post ) {

	// If this is just a revision, don't continue
	if ( wp_is_post_revision( $post->ID ) )
		return;

	if ( $post->post_type == 'montera34_collabora' && $post->post_status == 'publish' ) {
		$collabora_projects_count = count( get_post_meta( $post->ID, '_montera34_collabora_projects', true ) );
		if ( $collabora_projects_count >= 1 ) {
			update_post_meta($post->ID, '_montera34_collabora_projects_count', $collabora_projects_count);
		}
	}
	}

} // END count how many projects a collaborator has and add the number as a CF

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
		$query->set( 'order','DESC');
		$query->set( 'orderby','meta_value_num date');
		$query->set( 'meta_key','_montera34_project_card_date_ini');
	}
	elseif ( !is_admin() && is_post_type_archive('montera34_project') && $query->is_main_query() || !is_admin() && is_tax('montera34_type') && $query->is_main_query() ) {
		$query->set( 'order','DESC');
		$query->set( 'orderby','meta_value_num date');
		$query->set( 'meta_key','_montera34_project_card_date_ini');
	}
	elseif ( !is_admin() && is_post_type_archive('montera34_collabora') && $query->is_main_query() ) {
		$query->set( 'orderby', array ('meta_value_num' => 'DESC', 'title' => 'ASC' ) );
		$query->set( 'meta_key','_montera34_collabora_projects_count');
		$query->set( 'nopaging',true);
	}
	elseif ( !is_admin() && is_author() && $query->is_main_query() ) {
		$query->set( 'post_type',array('montera34_project'));
	}
	return $query;
} // END custom args for loops

// polylang translation plugin functions
function montera34_translate_copy_post_metas($metas) {
	$prefix = "_montera34_";
      return array_merge($metas, array($prefix. 'project_card_date_ini',$prefix. 'project_card_date_end',$prefix. 'project_card_project_url',$prefix. 'project_card_code_repo',$prefix. 'project_card_code_license',$prefix. 'project_card_money',$prefix . 'collabora_firstname',$prefix . 'collabora_lastname',$prefix . 'collabora_url',$prefix . 'collabora_twitter'));
}

function montera34_author_translation_url($url, $lang) {
	if (is_author()) {
		global $polylang;
		return $polylang->links->get_archive_url($polylang->model->get_language($lang));
	}
	return $url;
}

// Filter body_class function
function montera34_body_classes($classes) {
	global $post;
	if ( !is_admin() && is_single() && get_post_type() == 'montera34_project' ) {
		$types = get_the_terms($post->ID,'montera34_type');
		if ( $types != '' ) {
			foreach ( $types as $type ) {
        			$classes[] = "type-" .$type->slug;
				
			}
		}
	}
        return $classes;
} // END Filter body_class function
?>
