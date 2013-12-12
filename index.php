<?php get_header(); ?>

<?php
// MAIN CONTENT

if ( is_home() ) {
// if is home
	// main title
	$page_tit = "Desarrollamos webs e investigamos en internet, por ejemplo";

} else {
	// main title
	$page_tit = $wp_query->queried_object->name;
} // end if home
?>
		<header><h1><?php echo $page_tit ?></h1></header>
		<div class="row">
			<div class="col-md-8">
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
			</div>
	
			<div class="col-md-4"><!-- side bar 2--></div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
