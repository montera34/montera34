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

<?php
// metatags generation
if ( is_single() || is_page() ) {
	$metadesc = $post->post_excerpt;
	if ( $metadesc == '' ) { $metadesc = $post->post_content; }
	$metadesc = wp_strip_all_tags($post->post_content);
	$metadesc = strip_shortcodes( $metadesc );
	$metadesc_fb = substr( $metadesc, 0, 297 );
	$metadesc_tw = substr( $metadesc, 0, 200 );
	$metadesc = substr( $metadesc, 0, 154 );
	$metatit = $post->post_title;
	//$metatit = wp_title("",FALSE);
	$metatype = "article";
} else {
	$metadesc = MONTERA34_BLOGDESC;
	$metadesc_tw = MONTERA34_BLOGDESC;
	$metadesc_fb = MONTERA34_BLOGDESC;
	$metatit = MONTERA34_BLOGNAME;
	//$metatit = wp_title("",FALSE);
	$metatype = "blog";
}
	$metaperma = get_permalink();
?>

<!-- generic meta -->
<meta content="montera34" name="author" />
<meta content="<?php echo MONTERA34_BLOGDESC ?>" name="description" />
<meta content="<?php _e('free software, open data, wordpress, web development, HTML5, twitter API, processing, Linux, Debian, data visualization','montera34' ); ?>" name="keywords" />
<!-- facebook meta -->
<meta property="og:title" content="<?php echo $metatit ?>" />
<meta property="og:type" content="<?php echo $metatype ?>" />
<meta property="og:description" content="<?php echo $metadesc_fb ?>" />
<meta property="og:url" content="<?php echo $metaperma ?>" />
<!-- twitter meta -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@montera34">
<meta name="twitter:title" content="<?php echo $metatit ?>" />
<meta name="twitter:description" content="<?php echo $metadesc_tw ?>" />
<meta name="twitter:creator" content="@montera34">

<!-- twitter analytics
<meta property="twitter:account_id" content="1491442110" />-->

<link rel="profile" href="http://gmpg.org/xfn/11" />
<!-- Bootstrap stylesheet -->
<link rel="stylesheet" href="<?php echo MONTERA34_BLOGTHEME; ?>/css/bootstrap.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="alternate" type="application/rss+xml" title="<?php echo MONTERA34_BLOGNAME; ?> RSS Feed suscription" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php echo MONTERA34_BLOGNAME; ?> Atom Feed suscription" href="<?php bloginfo('atom_url'); ?>" /> 
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); ?>

</head>

<?php // better to use body tag as the main container ?>
<body <?php body_class(); ?>>
<div class="container">

<div class="row">
	<div id="pre" class="col-md-3">
		<div id="pre-logo">
			<a href="<?php echo MONTERA34_BLOGURL ?>" title="<?php _e('Go to home page','montera34'); ?>">
			<img src="<?php echo MONTERA34_BLOGTHEME . "/images/m34_logo.png"; ?>" alt="montera34 logo" /><br />
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
	</div> <!-- first side bar --> <!-- #pre -->

	<div id="content" class="col-md-9">
