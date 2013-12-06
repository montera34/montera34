<?php get_header(); ?>

<?php
// MAIN CONTENT

// home and type tax archive common conditions
$args = array(
	'post_type' => 'montera34_project',
	//'post_type' => 'page',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'menu_order',
);

if ( is_home() ) {
// if is home
	// main title
	$page_tit = "Searching good title for home page";
	// loop args: add sticky project condition
	$args['meta_query'] = array(
		array(
			'key' => '_montera34_project_sticky',
			'compare' => '=',
			'value' => 'on'
		),
	);
} else {
	// main title
	$page_tit = $wp_query->queried_object->name;
} // end if home
?>
<header>
<h1><?php echo $page_tit ?></h1>
</header>

<section>

<?php $the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {
	$page_tit = get_query_var('montera34_type');

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
	echo "<p>no projects.</p>";
} ?>
</section>

<?php get_footer(); ?>
