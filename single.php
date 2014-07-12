<?php get_header(); ?>

<?php
if ( have_posts() ) {
	while ( have_posts() ) : the_post();


// if is a project
if ( get_post_type() == 'montera34_project' ) {
	// type tax
	$types = get_the_terms($post->ID,'montera34_type');
	if ( $types != '' ) {
		$types_out = "";
		foreach ( $types as $term ) {
			$type_name = $term->name;
			$type_perma = get_term_link($term);
			$types_out .= "<a href='" .$type_perma. "' title='More projects in " .$type_name. "'>" .$type_name. "</a> ";
		}
	}
	// project URL
	$project_url = get_post_meta( $post->ID, '_montera34_project_card_url', true );
	if ( $project_url != '' ) { $project_url = "<a href='" .$project_url. "'>" .$project_url. "</a>"; }
	// project code repo URL
	$project_code_repo = get_post_meta( $post->ID, '_montera34_project_card_code_repo', true ); //print_r($project_code_repo);
	if ( $project_code_repo != '' ) { $project_code_repo_out = "<a href='" .$project_code_repo[0]['url']. "'>" .$project_code_repo[0]['url_text']. "</a>"; }
	// project code license URL
	$project_code_license = get_post_meta( $post->ID, '_montera34_project_card_code_license', true );
	if ( $project_code_license != '' ) { $project_code_license_out = "<a href='" .$project_code_license[0]['url']. "'>" .$project_code_license[0]['url_text']. "</a>"; }

	// card items array
	$card_items = array(
		'Type' => $types_out,
		'Status'=> get_post_meta( $post->ID, '_montera34_project_card_status', true ),
		'Project URL' => $project_url,
		'Client' => get_post_meta( $post->ID, '_montera34_project_card_client', true ),
		'Code repository' => $project_code_repo_out,
		'Code license' => $project_code_license_out,
	);

// if is a collaborator
} elseif ( get_post_type() == 'montera34_collaborator' ) {
	// card items array
	$card_items = array(
		'Firsname' => get_post_meta( $post->ID, '_montera34_collabora_firstname', true ),
		'Lastname' => get_post_meta( $post->ID, '_montera34_collabora_lastname', true ),
		'Website' => get_post_meta( $post->ID, '_montera34_collabora_url', true ),
		'Twitter' => get_post_meta( $post->ID, '_montera34_collabora_twitter', true ),
	);

} // end vars depending on post type

// common vars
$tit = get_the_title();
$content = apply_filters( 'the_content',get_the_content() );
$desc = ": " .strip_tags( get_the_excerpt() );
?>

		<header>
		<h1><?php echo $tit; ?></h1>
		</header>
		
		<div class="row">
			<div class="col-md-8">
				<section>
				<?php echo $content; ?>
				</section>
			</div>
	
			<div class="col-md-4"><!-- side bar 2 -->
				<section>
				<dl>
					<?php foreach ($card_items as $key => $value ) {
						echo "<dt><strong>" .$key. "</strong></dt>
									<dd>" .$value. "</dd>";
					} ?>
				</dl>
				</section>
	<?php endwhile;
	//wp_reset_postdata();

//} else {
}

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
