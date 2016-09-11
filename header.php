<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title>
<?php
	/* From twentyeleven theme
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	echo MONTERA34_BLOGNAME;

	// Add the blog description for the home/front page.
	if ( MONTERA34_BLOGDESC && ( is_home() || is_front_page() ) )
		echo " | " . MONTERA34_BLOGDESC;

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'montera34' ), max( $paged, $page ) );

	?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php
// metatags generation
if ( is_single() || is_page() ) {
	$metadesc = $post->post_excerpt;
	if ( $metadesc == '' ) { $metadesc = $post->post_content; }
	$metadesc = wp_strip_all_tags($metadesc);
	$metadesc = strip_shortcodes( $metadesc );
	$metadesc = str_replace( array('"','\''), '', $metadesc );
	$metadesc_fb = substr( $metadesc, 0, 297 );
	$metadesc_tw = substr( $metadesc, 0, 200 );
	$metatit = str_replace( array('"','\''), '', $post->post_title );
	$metadesc = $metatit.": ".$metadesc;
	$metadesc = substr( $metadesc, 0, 154 );
	$img_id = get_post_thumbnail_id();
	if ( $img_id != '' ) {
		$wp_size = "large";
		$img_src = wp_get_attachment_image_src($img_id,$wp_size, true);
		$img_metadata = wp_get_attachment_metadata($img_id);
		$metaimg = $img_src[0];
		$metaimg_width = $img_metadata['sizes'][$wp_size]['width'];
		$metaimg_height = $img_metadata['sizes'][$wp_size]['height'];
		$metaimg_type = $img_metadata['sizes'][$wp_size]['mime-type'];

	} else {
		$metaimg = "https://montera34.com/wp-content/themes/montera34/screenshot.png";
		$metaimg_width = '880';
		$metaimg_height = '660';
		$metaimg_type = 'image/png';

	}
	$metatype = "article";
	$metaperma = get_permalink();

} else {
	$metadesc = MONTERA34_BLOGDESC;
	$metadesc_tw = MONTERA34_BLOGDESC;
	$metadesc_fb = MONTERA34_BLOGDESC;
	$metatit = MONTERA34_BLOGNAME;
	$metatype = "website";
	$metaimg = "https://montera34.com/wp-content/themes/montera34/screenshot.png";
	$metaimg_width = '880';
	$metaimg_height = '660';
	$metaimg_type = 'image/png';
	$metaperma = MONTERA34_BLOGURL;
}
?>

<!-- generic meta -->
<meta content="<?php echo $metadesc ?>" name="description" />
<meta content="<?php _e('free software, open data, wordpress, web development, HTML5, twitter API, processing, Linux, Debian, data visualization','montera34' ); ?>" name="keywords" />
<!-- facebook meta -->
<meta property="og:site_name" content="Archivo montera34"/>
<meta property="og:title" content="<?php echo $metatit ?>" />
<meta property="og:type" content="<?php echo $metatype ?>" />
<meta property="og:description" content="<?php echo $metadesc_fb ?>" />
<meta property="og:image" content="<?php echo $metaimg ?>" />
<meta property="og:image:type" content="<?php echo $metaimg_type ?>" />
<meta property="og:image:width" content="<?php echo $metaimg_width ?>" />
<meta property="og:image:height" content="<?php echo $metaimg_height ?>" />
<meta property="og:url" content="<?php echo $metaperma ?>" />
<meta property="og:locale" content="<?php echo get_locale(); ?>" />
<meta property="article:author" content="https://www.facebook.com/skotperez" />
<!-- twitter meta -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@montera34">
<meta name="twitter:title" content="<?php echo $metatit ?>" />
<meta name="twitter:description" content="<?php echo $metadesc_tw ?>" />
<meta name="twitter:creator" content="@montera34">
<meta name="twitter:image:src" content="<?php echo $metaimg ?>">

<link rel="profile" href="http://gmpg.org/xfn/11" />
<!-- Bootstrap stylesheet -->
<link rel="stylesheet" href="<?php echo MONTERA34_BLOGTHEME; ?>/css/bootstrap.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="alternate" type="application/rss+xml" title="<?php echo MONTERA34_BLOGNAME; ?> RSS Feed suscription" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php echo MONTERA34_BLOGNAME; ?> Atom Feed suscription" href="<?php bloginfo('atom_url'); ?>" /> 
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php
// if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); ?>

</head>

<?php // better to use body tag as the main container ?>
<body <?php body_class(); ?>>
<div class="container">

<div class="row">
	<div id="pre" class="col-md-3">
		<div id="pre-logo">
			<a href="<?php echo get_home_url(); ?>" title="<?php _e('Go to home page','montera34'); ?>">
			<img src="<?php echo MONTERA34_BLOGTHEME . '/images/m34_logo.png'; ?>" alt="montera34 logo" /><br />
			<strong><?php echo MONTERA34_BLOGNAME ?></strong>
			</a>
		</div>
		<div><?php echo MONTERA34_BLOGDESC ?></div>
		<?php $defaults = array(
				'theme_location'  => 'sidebar-menu',
				'menu_id' => 'pre-menu',
				);
			wp_nav_menu( $defaults );?>	

		<?php get_search_form(); ?>
		<?php if ( function_exists('pll_the_languages') ) {
			$args = array(
				'hide_if_no_translation' => 1
			);
			echo '<ul class="pre-lang-switcher">'; pll_the_languages($args); echo '</ul>';
		} ?>
			<div id="social-networks"><?php _e('Montera34 in','montera34'); ?>
			<a href="https://www.facebook.com/montera34" title="Montera34 Facebook"><i class='fa fa-facebook'></i></a>
			<a href="https://twitter.com/montera34" title="Montera34 Twitter"><i class='fa fa-twitter'></i></a>
			<a href="https://plus.google.com/+Montera34" title="Montera34 Google Plus"><i class='fa fa-google-plus'></i></a>
		</div>
	</div> <!-- first side bar --> <!-- #pre -->

	<div id="content" class="col-md-9">
