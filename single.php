<?php get_header(); ?>

<?php
//if ( have_posts() ) {

	while ( have_posts() ) : the_post();
		// type tax
		$types = get_the_terms($post_id,'montera34_type');
		$types_out = "";
		foreach ( $types as $term ) {
			$type_name = $term->name;
			$type_perma = get_term_link($term);
			$types_out .= "<a href='" .$type_perma. "' title='More projects in " .$type_name. "'>" .$type_name. "</a> ";
		}
		// status tax
		$status = get_the_terms($post_id,'montera34_status');
		$status_out = "";
		foreach ( $status as $term ) {
			$status_name = $term->name;
			$status_perma = get_term_link($term);
			$status_out .= "<a href='" .$status_perma. "' title='More projects in " .$status_name. "'>" .$status_name. "</a> ";
		}
		// card items array
		$card_items = array(
			array('Type',$types_out),
			array('Status',$status_out),
			array('Project URL',get_post_meta( $post->ID, '_montera34_project_card_url', true )),
			array('Client',get_post_meta( $post->ID, '_montera34_project_card_client', true )),
			array('Code repository',get_post_meta( $post->ID, '_montera34_project_card_code_repo', true )),
			array('Code license',get_post_meta( $post->ID, '_montera34_project_card_code_license', true )),
		);
?>
		<header>
		<h1><?php the_title(); ?></h1>
		</header>

		<section>
		<?php the_content(); ?>
		</section>

		<section>
		<ul>
			<?php foreach ($card_items as $item ) {
				echo "<li><strong>" .$item[0]. "</strong>. " .$item[1]. "</li>";
			} ?>
		</ul>
		</section>
	<?php endwhile;
	//wp_reset_postdata();

//} else {
//} ?>



<?php get_footer(); ?>
