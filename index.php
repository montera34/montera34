<?php get_header(); ?>

<?php
// MAIN CONTENT

if ( is_home() ) {
// if is home
	// main title
	$page_tit = "Searching good title for home page";

} else {
	// main title
	$page_tit = $wp_query->queried_object->name;
} // end if home
?>
<header>
<h1><?php echo $page_tit ?></h1>
</header>

<section>

<?php
if ( have_posts() ) {

	// The Loop
	while ( have_posts() ) : the_post();
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
