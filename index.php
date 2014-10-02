<?php get_header(); ?>

<?php
global $wp_post_types;
// MAIN CONTENT

// build main title 
if ( is_home() ) {
	// if is home
	$loop_name = "list";
	$page_tit = __('We make projects in the internet, for us and for others. For example:','montera34');
	$page_subtit = "";

} elseif ( is_search() ) {
	// if search
	$loop_name = "list";
	$query_s = $wp_query->query_vars['s'];
	$page_tit = sprintf( __('Search results for <strong>%s</strong>','montera34'), $query_s );
	$page_subtit = "";

} elseif ( is_author() ) {
	// if author archive
	$loop_name = "list";
	$page_tit = get_the_author_meta( 'display_name' );
	$page_subtit = "<small>" .__('In charge of the following projects.','montera34'). "</small>";

} else {
	// if archive
	$pt_current = get_post_type();
	if ( $pt_current == 'montera34_project' ) {
		$loop_name = "list";
		$page_subtit = "<small>" .__('by year of publication.','montera34'). "</small>";

	} elseif ( $pt_current == 'montera34_collabora' ) {
		$loop_name = "media-list";
		$page_subtit = "<small>" .__('by number of projects.','montera34'). "</small>";

	}
	$page_tit = $wp_post_types[$pt_current]->labels->name;
	if ( is_tax() ) {
		$term_tit = single_term_title( ': ', FALSE );
		$page_tit .= $term_tit;
	}

} // end build main title

// build right sidebar
if ( is_author() ) {
	$author_id = get_the_author_meta( 'ID' );
	$author_name = get_the_author_meta( 'display_name' );
	$author_bio = get_the_author_meta( 'description' );
	$author_website = get_the_author_meta( 'user_url' );
	$author_website_out = "<a href='" .$author_website. "'>" .$author_website. "</a>";

	$sidebar_right_out[$author_name] = $author_bio;
	$sidebar_right_out[__('Sitio web','montera34')] = $author_website_out;

	//$author_img_out = get_avatar( $author_id, 190, $author_img, $author_alt );
	$author_img_out = "<figure>" .get_avatar( $author_id, 190 ). "</figure>";
} else {
	$sidebar_right_out = "";
	$author_img_out = "";
}
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
					echo "<p>".__('No content.','montera34'). "</p>";
				}
				include "pagination.php"; ?>
				</div><!-- .<?php echo $loop_name ?> -->
			</div>

			<?php // if sidebar right exists
			if ( $sidebar_right_out != '' ) { ?>
			<aside id='sidebar-right' class="col-md-3">
					<?php echo $author_img_out; ?>
					<dl>
					<?php foreach ($sidebar_right_out as $key => $value ) {
						echo "<dt><strong>" .$key. "</strong></dt>
									<dd>" .$value. "</dd>";
					} ?>
					</dl>
			</aside><!-- #sidebar-right -->
			<?php } // end if sidebar right exists ?>
		</div><!-- .row -->

<?php get_footer(); ?>
