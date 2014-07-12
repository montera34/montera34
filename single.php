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
	if ( $project_url != '' ) { $project_url_out = "<a href='" .$project_url. "'>" .$project_url. "</a>"; }
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
		'Project URL' => $project_url_out,
		'Client' => get_post_meta( $post->ID, '_montera34_project_card_client', true ),
		'Code repository' => $project_code_repo_out,
		'Code license' => $project_code_license_out,
	);
	// building collaborators section
	$project_collaboras = get_post_meta( $post->ID, '_montera34_collabora', true );
	$args = array(
		'posts_per_page' => -1,
		'post__in' => $project_collaboras,
		'post_type' => 'montera34_collabora'
	);
	$collaboras = get_posts($args);
	if ( count($collaboras) != 0 ) {
	$collaboras_out = "<section id='collaborators'><h3>Collaborators</h3><div class='media-list'>";
		foreach ( $collaboras as $collabora ) {
			$collabora_perma = get_permalink($collabora->ID);
			$collabora_tit = $collabora->post_title;
			if ( has_post_thumbnail($collabora->ID) ) {
				$collabora_img = get_the_post_thumbnail( $collabora->ID, 'bigicon', array('class' => 'media-object') );
				$collabora_img_out = "<a class='pull-left' href='" .$collabora_perma. "' title='" .$collabora_tit. "'>" .$collabora_img. "</a>";
			} else { $collabora_img_out = ""; }
			$collabora_roles = get_post_meta( $collabora->ID, '_montera34_collabora_projects', true );
			foreach ( $collabora_roles as $rol ) {
				if ( $rol['project'] == $post->ID ) { $collabora_rol = $rol['rol']; }
			}
			$collaboras_out .=
			"<div class='media'>" .$collabora_img_out. "
				<div class='media-body'>
					<h4 class='media-heading'><a href='" .$collabora_perma. "' title='" .$collabora_tit. "'>" .$collabora_tit. "</a></h4>
					<p>" .$collabora_rol. "</p>
			</div>
			";
		} // end foreach collaborators
	} // end if collaborators
	$collaboras_out .= "</div></section>";

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
		$related_out = "<section><h3>Parent Project</h3><ul>";
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
		}
	} // end if project is parent
	// end related projects
	$collabora_img_out = "";

// if is a collaborator
} elseif ( get_post_type() == 'montera34_collabora' ) {
	if ( has_post_thumbnail() ) {
		$collabora_img = get_the_post_thumbnail( $post->ID, 'small', array('class' => 'img-responsive') );
		$collabora_img_out = "<figure>" .$collabora_img. "</figure>";
	} else { $collabora_img_out = ""; }
	// card items array
	// collaborator URL
	$collabora_url = get_post_meta( $post->ID, '_montera34_collabora_url', true );
	if ( $collabora_url != '' ) { $collabora_url_out = "<a href='" .$collabora_url. "'>" .$collabora_url. "</a>"; }
	// twitter URL
	$collabora_twitter = get_post_meta( $post->ID, '_montera34_collabora_twitter', true );
	if ( $collabora_twitter != '' ) { $collabora_twitter_out = "<a href='" .$collabora_twitter. "'>" .$collabora_twitter. "</a>"; }
	$card_items = array(
		'Firstname' => get_post_meta( $post->ID, '_montera34_collabora_firstname', true ),
		'Lastname' => get_post_meta( $post->ID, '_montera34_collabora_lastname', true ),
		'Website' => $collabora_url_out,
		'Twitter' => $collabora_twitter_out,
	);
	$collaboras_out = "";
	$related_out = "";

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
					<?php echo $collabora_img_out; ?>
					<dl>
					<?php foreach ($card_items as $key => $value ) {
						echo "<dt><strong>" .$key. "</strong></dt>
									<dd>" .$value. "</dd>";
					} ?>
					</dl>
				</section>
				<?php echo $collaboras_out;
				echo $related_out; ?>
	<?php endwhile;
} // end if posts
?>

			</div><!-- end side bar 2-->
		</div><!-- .row -->
	</div>
</div>

<?php get_footer(); ?>
