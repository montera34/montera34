<?php get_header(); ?>

<?php
//if ( have_posts() ) {

	while ( have_posts() ) : the_post();
		// type tax
		$types = get_the_terms($post->ID,'montera34_type');
		$types_out = "";
		foreach ( $types as $term ) {
			$type_name = $term->name;
			$type_perma = get_term_link($term);
			$types_out .= "<a href='" .$type_perma. "' title='More projects in " .$type_name. "'>" .$type_name. "</a> ";
		}
		// card items array
		$card_items = array(
			array('Type',$types_out),
			array('Status',get_post_meta( $post->ID, '_montera34_project_card_status', true )),
			array('Project URL',get_post_meta( $post->ID, '_montera34_project_card_url', true )),
			array('Client',get_post_meta( $post->ID, '_montera34_project_card_client', true )),
			array('Code repository',get_post_meta( $post->ID, '_montera34_project_card_code_repo', true )),
			array('Code license',get_post_meta( $post->ID, '_montera34_project_card_code_license', true )),
		);
?>
		<header>
		<h1><?php the_title();
				$excerpt = strip_tags(get_the_excerpt());
        echo ": " .$excerpt; ?></h1>
		</header>
		
		<div class="row">
			<div class="col-md-8">
				<section>
				<?php the_content(); ?>
				</section>
			</div>
	
			<div class="col-md-4"><!-- side bar 2-->
				<section>
				<dl>
					<?php foreach ($card_items as $item ) {
						echo "<dt><strong>" .$item[0]. "</strong></dt>
									<dd>" .$item[1]. "</dd>";
					} ?>
				</dl>
				</section>
	<?php endwhile;
	//wp_reset_postdata();

//} else {
//}

// related projects
if ( $post->post_parent != '0' ) {
// if project is child
	$args = array(
		'post_parent' => $post->post_parent,
		'posts_per_page' => -1,
		'exclude' => $post->ID,
		'post_type' => 'montera34_project'
	);
	$related_projects = get_posts($args);
	$related_count = count($related_projects);
	$related_type = "child";
	//if ( $related_count == '0' ) { $related_count = 1; }
	//echo count($related_projects);
	//print_r($related_projects);
	$related_out = "<section><h3>Parent Project</h3><ul>"; //TODO estart in cluding getext _e('Parent Project','montera34');
	$related_out .= "<li><a href='" .get_permalink($post->post_parent). "'>" .get_the_title($post->post_parent). "</a></li>";
	if ( $related_count != '0' ) {
		foreach ( $related_projects as $related ) {
			$related_out .= "<li><a href='" .get_permalink($related->ID). "'>" .$related->post_title. "</a></li>";
		}
	}
	$related_out .= "</ul></section>";
	echo $related_out;

} else {
// if project is parent
	$args = array(
		'post_parent' => $post->ID,
		'posts_per_page' => -1,
		'post_type' => 'montera34_project'
	);
	$related_projects = get_posts($args);
	$related_count = count($related_projects);

	if ( $related_count != '0' ) {
	// if project has children
		//echo "Project is parent and has children.";
		$related_out = "<section><h3>Related Projects</h3><ul>";
		foreach ( $related_projects as $related ) {
			$related_out .= "<li><a href='" .get_permalink($related->ID). "'>" .$related->post_title. "</a></li>";
		}
		$related_out .= "</ul></section>";
		echo $related_out;
	}
} // end if project is parent
// end related projects
	
?>


			</div><!-- end side bar 2-->
		</div>
	</div>
</div>

<?php get_footer(); ?>
