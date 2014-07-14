<?php get_header(); ?>

<?php
global $wp_post_types;
// MAIN CONTENT

// build main title 
if ( is_home() ) {
	// if is home
	$page_tit = "Desarrollamos webs e investigamos en internet, por ejemplo";

} elseif ( is_search() ) {
	// if search
	$query_s = $wp_query->query_vars['s'];
	$page_tit = "Search results for '<strong>$query_s</strong>'";

} else {
	// if archive
	$pt_current = get_post_type();
	$page_tit = $wp_post_types[$pt_current]->labels->name;
	if ( is_tax() ) {
		$term_tit = single_term_title( ': ', FALSE );
		$page_tit .= $term_tit;
	}

} // end build main title
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

				} else {
					echo "<p>no projects.</p>";
				} ?>
				</section>
			</div>
	
		</div>
	</div>
</div>

<?php get_footer(); ?>
