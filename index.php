<?php get_header(); ?>

<?php
global $wp_post_types;
// MAIN CONTENT

// build main title 
if ( is_home() ) {
	// if is home
	$loop_name = "list";
	$page_tit = __('We\'ll make your website, only if you need it.','montera34');
	$page_subtit = "";

} elseif ( is_search() ) {
	// if search
	$loop_name = "list";
	$query_s = $wp_query->query_vars['s'];
	$page_tit = sprintf( __('Search results for <strong>%s</strong>','montera34'), $query_s );
	$page_subtit = "";

} else {
	// if archive
	$pt_current = get_post_type();
	if ( $pt_current == 'montera34_project' ) {
		$loop_name = "list";
		$page_subtit = "<small>" .__('By year of publication.','montera34'). "</small>";

	} elseif ( $pt_current == 'montera34_collabora' ) {
		$loop_name = "media-list";
		$page_subtit = "<small>" .__('Alphabetical order.','montera34'). "</small>";

	}
	$page_tit = $wp_post_types[$pt_current]->labels->name;
	if ( is_tax() ) {
		$term_tit = single_term_title( ': ', FALSE );
		$page_tit .= $term_tit;
	}

} // end build main title
?>
		<header class="main-tit">
			<h1><?php echo $page_tit ?></h1>
			<?php echo $page_subtit ?>
		</header>
		<div class="row">
			<div class="col-md-9">
				<div class='<?php echo $loop_name ?>'>
				<?php
				if ( have_posts() ) {

					// The Loop
					while ( have_posts() ) : the_post();
						include "loop." .$loop_name. ".php";
					endwhile;

				} else {
					echo "<p>i".__('No content.','montera34'). "</p>";
				}
				include "pagination.php"; ?>
				</div><!-- .<?php echo $loop_name ?> -->
			</div>
	
		</div><!-- .row -->

<?php get_footer(); ?>
