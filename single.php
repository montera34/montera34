<?php get_header(); ?>

<?php
if ( have_posts() ) {
	while ( have_posts() ) : the_post();

// common vars
$tit = get_the_title();
$content = apply_filters( 'the_content',get_the_content() );

// if is a project
if ( get_post_type() == 'montera34_project' ) {
	$subtit = get_the_excerpt();
	// card items array
	// description
//	if ( $desc != '' ) { $card_items['Description'] = $desc; }
	// type and tech tax
	$taxes = array(
		'Type' => 'montera34_type',
		'Technologies' => 'montera34_tech'
	);
	foreach ( $taxes as $tax_tit => $tax ) {
		$terms = get_the_terms($post->ID,$tax);
		if ( $terms != '' ) {
			$terms_out = "";
			foreach ( $terms as $term ) {
				$term_name = $term->name;
				$term_perma = get_term_link($term);
				$term_out .= "<a href='" .$term_perma. "' title='More projects in " .$term_name. "'>" .$term_name. "</a> ";
			}
			$card_items[$tax_tit] = $term_out;
			unset($term_out);
		}
	} // end loop taxes
	// year
	$project_year = get_post_meta( $post->ID, '_montera34_project_card_date_ini', true );
	if ( $project_year != '' ) { $card_items['Year'] = $project_year; }
	// client
	$project_client = get_post_meta( $post->ID, '_montera34_project_card_client', true );
	if ( $project_client != '' ) { $card_items['Client'] = $project_client; }
	// project URL
	$project_url = get_post_meta( $post->ID, '_montera34_project_card_project_url', true );
	if ( $project_url != '' ) { $card_items['Project URL'] = "<a href='" .$project_url. "'>" .$project_url. "</a>"; }
	// project code repo URL
	$project_code_repo = get_post_meta( $post->ID, '_montera34_project_card_code_repo', true ); 
	if ( $project_code_repo != '' ) { $card_items['Code repository'] = "<a href='" .$project_code_repo[0]['url']. "'>" .$project_code_repo[0]['url_text']. "</a>"; }
	// project code license URL
	$project_code_license = get_post_meta( $post->ID, '_montera34_project_card_code_license', true );
	if ( $project_code_license != '' ) { $card_items['Code license'] = "<a href='" .$project_code_license[0]['url']. "'>" .$project_code_license[0]['url_text']. "</a>"; }
	// status
	$project_status = get_post_meta( $post->ID, '_montera34_project_card_status', true );
	if ( $project_status != '' ) { $card_items['Status'] = $project_status; }

	// building collaborators section
	$project_collaboras = get_post_meta( $post->ID, '_montera34_collabora', true );
	if ( $project_collaboras != '' ) {
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
				unset($collabora_rol);
			} // end foreach collaborators
		} // end if collaborators
		$collaboras_out .= "</div></section>";
	} // end if there is collaboras

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
	$collabora_projects = "";

// if is a collaborator
} elseif ( get_post_type() == 'montera34_collabora' ) {
	$subtit = "";
	if ( has_post_thumbnail() ) {
		$collabora_img = get_the_post_thumbnail( $post->ID, 'small', array('class' => 'img-responsive') );
		$collabora_img_out = "<figure>" .$collabora_img. "</figure>";
	} else { $collabora_img_out = ""; }
	// card items array
	// collaborator URL
	$collabora_url = get_post_meta( $post->ID, '_montera34_collabora_url', true );
	if ( $collabora_url != '' ) { $card_items['Website'] = "<a href='" .$collabora_url. "'>" .$collabora_url. "</a>"; }
	// twitter URL
	$collabora_twitter = get_post_meta( $post->ID, '_montera34_collabora_twitter', true );
	if ( $collabora_twitter != '' ) { $card_items['Twitter'] = "<a href='" .$collabora_twitter. "'>" .$collabora_twitter. "</a>"; }
//	$card_items = array(
//		'Firstname' => get_post_meta( $post->ID, '_montera34_collabora_firstname', true ),
//		'Lastname' => get_post_meta( $post->ID, '_montera34_collabora_lastname', true ),
//	);
	$collaboras_out = "";
	$related_out = "";

	// projects
	$collabora_projects = get_post_meta( $post->ID, '_montera34_collabora_projects', true );
	if ( $collabora_projects != '' ) {
		foreach ( $collabora_projects as $project ) {
			$project_ids[] = $project['project'];
		}
		$args = array(
			'posts_per_page' => -1,
			'post__in' => $project_ids,
			'post_status' => array( 'publish', 'private' ),
			'post_type' => 'montera34_project'
		);
		$projects = get_posts($args);
		$collabora_projects_out = "<section id='projects'><h3>Projects in which " .$tit. " has collaborated</h3><div class='list'>";
			foreach ( $projects as $project ) {
				$project_perma = get_permalink($project->ID);
				$project_tit = $project->post_title;
				$project_desc = $project->post_excerpt;
				if ( has_post_thumbnail($project->ID) ) {
					$project_img = "<figure><a href=" .$project_perma. ">" .get_the_post_thumbnail($project->ID,'thumbnail',array('class' => 'img-responsive')). "</a></figure>";
				} else { $collabora_img = ""; }
				$project_roles = get_post_meta( $post->ID, '_montera34_collabora_projects', true );
				foreach ( $project_roles as $rol ) {
					if ( $rol['project'] == $project->ID ) { $project_rol = $rol['rol']; }
				}
				$collabora_projects_out .=
				"<div class='list-item list-project'>
					<header><h4 class='list-item-tit'><a href='" .$project_perma. "' title='" .$project_tit. "'>" .$project_tit. "</a></h4>
					<div class='list-item-desc'>
						<div class='list-item-rol'>Rol: " .$project_rol. "</div>
						<p>" .$project_desc. "</p>
						" .$project_img. "
					</div>
				</div>
				";
			} // end foreach collaborators
		$collabora_projects_out .= "</div></section>";
	}

} // end vars depending on post type
?>

		<header class="main-tit">
			<h1><?php echo $tit; ?></h1>
			<?php echo $subtit ?>
		</header>
		
		<div class="row">
			<div class="col-md-8">
				<section>
				<?php echo $content; ?>
				</section>
				<?php echo $collabora_projects_out; ?>
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
