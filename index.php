<?php get_header(); ?>

<?php
$args = array(
	'post_type' => 'montera34_project',
	//'post_type' => 'page',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'menu_order',
//	'meta_query' => array(
//		array(
//			'key' => '_montera34_home_sticky',
//			'compare' => '=',
//			'value' => 'on'
//		),
//	),
);
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {

	// The Loop
	while ( $the_query->have_posts() ) : $the_query->the_post();
		include "loop.php";
	endwhile;
	/* Restore original Post Data 
	 * NB: Because we are using new WP_Query we aren't stomping on the 
	 * original $wp_query and it does not need to be reset.
	*/
	wp_reset_postdata();

} else {
	echo "no projects.";
} ?>
<?php get_footer(); ?>
