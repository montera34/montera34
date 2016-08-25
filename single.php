<?php get_header(); ?>

<?php
if ( have_posts() ) {
	while ( have_posts() ) : the_post();

// common vars
$tit = get_the_title();
$content = apply_filters( 'the_content',get_the_content() );

// if is a project
if ( get_post_type() == 'montera34_project' ) {
	$subtit = "<div class='subtit'>" .get_the_excerpt(). "</div>";
	// card items array
	// description
//	if ( $desc != '' ) { $card_items['Description'] = $desc; }
	// type and tech tax
	$taxes = array(
		__('Type','montera34') => 'montera34_type',
		__('Technologies','montera34') => 'montera34_tech'
	);
	foreach ( $taxes as $tax_tit => $tax ) {
		$terms = get_the_terms($post->ID,$tax);
		if ( $terms != '' ) {
			$terms_out = "";
			foreach ( $terms as $term ) {
				$term_name = $term->name;
				$term_perma = get_term_link($term);
				$term_out .= "<a href='" .$term_perma. "' title='" .sprintf(__('More projects in %s'), $term_name). "'>" .$term_name. "</a> ";
			}
			$card_items[$tax_tit] = $term_out;
			unset($term_out);
		}
	} // end loop taxes
	// year
	$project_year_ini = get_post_meta( $post->ID, '_montera34_project_card_date_ini', true );
	$project_year_end = get_post_meta( $post->ID, '_montera34_project_card_date_end', true );
	if ( $project_year_ini != '' && $project_year_end == '' || $project_year_ini == $project_year_end ) { $card_items[__('Year of publication','montera34')] = $project_year_ini;
	} else { $card_items[__('Year of publication','montera34')] = $project_year_ini. "&#8212;" .$project_year_end; }
	// client
	$project_client = get_post_meta( $post->ID, '_montera34_project_card_client', true );
	if ( $project_client != '' ) { $card_items[__('Client','montera34')] = $project_client; }
	// project URL
	$project_url = get_post_meta( $post->ID, '_montera34_project_card_project_url', true );
	if ( $project_url != '' ) {
		$card_items[__('Project URL','montera34')] = "<a href='" .$project_url. "' title='" .__('Go to this project website','montera34'). "'>" .$project_url. "</a>";
		$subtit_url = "<div class='subtit-url'><span class='icon-link'></span> " .$card_items[__('Project URL','montera34')]. "</div>";
	} else { $subtit_link = ""; }
	// project code repo URL
	$project_code_repo = get_post_meta( $post->ID, '_montera34_project_card_code_repo', false);
	$repos = $project_code_repo[0];
	$repos_count = count($project_code_repo[0]);
	if ( $repos_count != 0 ) {
		$card_items[__('Code repository','montera34')] = "";
		$count = 0;
		while ( $repos_count >> $count ) { $card_items[__('Code repository','montera34')] .= "<a href='" .$repos[$count]['url']. "'>" .$repos[$count]['url_text']. "</a> "; $count++; }
	}
	// project code license URL
	$project_code_license = get_post_meta( $post->ID, '_montera34_project_card_code_license', true );
	if ( $project_code_license[0] != '' ) { $card_items[__('Code license','montera34')] = "<a href='" .$project_code_license[0]['url']. "'>" .$project_code_license[0]['url_text']. "</a>"; }
	// status
	$project_status = get_post_meta( $post->ID, '_montera34_project_card_status', true );
	if ( $project_status != '' ) { $card_items[__('Status','montera34')] = $project_status; }

	$project_responsible = "<a href='" .get_author_posts_url( get_the_author_meta( 'ID' ) ). "'>" .get_the_author_meta( 'display_name' ). "</a>";
	$card_items[__('In charge','montera34')] = $project_responsible;

	// building collaborators section
	$project_collaboras = get_post_meta( $post->ID, '_montera34_collabora', true );
	if ( $project_collaboras != '' ) {
		$args = array(
			'posts_per_page' => -1,
			'post__in' => $project_collaboras,
			'post_status' => array( 'publish' ),
			'post_type' => 'montera34_collabora'
		);
		$collaboras = get_posts($args);
		if ( count($collaboras) != 0 ) {
		$collaboras_out = "<section id='collaborators'><h3 class='tit-upper'>" .__('Collaborators','montera34'). "</h3><div class='media-list'>";
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
		$parent = $post->post_parent;
		$args = array(
			'post_parent' => $parent,
			'posts_per_page' => -1,
			'exclude' => $post->ID,
			'post_type' => 'montera34_project'
		);
		$related_projects = get_posts($args);
		$related_count = count($related_projects);
		$related_type = "child";
	
	} else {
	// if project is parent
		$args = array(
//			'post_status' => array('publish','private'),
			'post_parent' => $post->ID,
			'posts_per_page' => -1,
			'post_type' => 'montera34_project'
		);
		$related_projects = get_posts($args);
		$related_count = count($related_projects);
		$related_type = "parent";
	
	} // end if project is parent

	if ( $related_count != '0' || $related_count == '0' && $related_type == 'child' ) {
	// if project has children
		$related_out = "<aside id='related'><h3 class='tit-upper'>" .__('Related Projects','montera34'). "</h3><ul class='list-unstyled'>";
		if ( $related_type == 'child' ) {
			if ( has_post_thumbnail($parent) ) {
				$rel_img = get_the_post_thumbnail( $parent, 'small', array('class' => 'img-responsive') );
				$rel_img_out = "<figure class='rel-img'>" .$rel_img. "</figure>";
			} else { $rel_img_out = ""; }
			$related_out .= "<li class='rel-item'>".$rel_img_out."<a href='" .get_permalink($parent). "'>" .get_the_title($parent). "</a></li>";
		}
		foreach ( $related_projects as $related ) {
			if ( has_post_thumbnail() ) {
				$rel_img = get_the_post_thumbnail( $related->ID, 'small', array('class' => 'img-responsive') );
				$rel_img_out = "<figure class='rel-img'>" .$rel_img. "</figure>";
			} else { $rel_img_out = ""; }
			$related_out .= "<li class='rel-item'>".$rel_img_out."<a href='" .get_permalink($related->ID). "'>" .$related->post_title. "</a></li>";
		}
		$related_out .= "</ul></aside>";
	} else { $related_out = ""; }
	// end related projects
	$collabora_img_out = "";
	$collabora_projects_out = "";

// if it is a collaborator
} elseif ( get_post_type() == 'montera34_collabora' ) {
	$subtit = "";
	if ( has_post_thumbnail() ) {
		$collabora_img = get_the_post_thumbnail( $post->ID, 'small', array('class' => 'img-responsive') );
		$collabora_img_out = "<figure>" .$collabora_img. "</figure>";
	} else { $collabora_img_out = ""; }
	// card items array
	// collaborator URL
	$collabora_url = get_post_meta( $post->ID, '_montera34_collabora_url', true );
	if ( $collabora_url != '' ) { $card_items[__('Website','montera34')] = "<a href='" .$collabora_url. "'>" .$collabora_url. "</a>"; }
	// twitter URL
	$collabora_twitter = get_post_meta( $post->ID, '_montera34_collabora_twitter', true );
	if ( $collabora_twitter != '' ) { $card_items['Twitter'] = "<a href='" .$collabora_twitter. "'>" .$collabora_twitter. "</a>"; }
//	$card_items = array(
//		'Firstname' => get_post_meta( $post->ID, '_montera34_collabora_firstname', true ),
//		'Lastname' => get_post_meta( $post->ID, '_montera34_collabora_lastname', true ),
//	);
	$collaboras_out = "";
	$related_out = "";
	$subtit_link = "";

	// projects
	$collabora_projects = get_post_meta( $post->ID, '_montera34_collabora_projects', true );
	if ( count($collabora_projects) >= 1 ) {
		foreach ( $collabora_projects as $project ) {
			$project_ids[] = $project['project'];
		}
		$args = array(
			'posts_per_page' => -1,
			'post__in' => $project_ids,
			'post_status' => array( 'publish' ),
			'post_type' => 'montera34_project',
			'order' => 'DESC',
			'orderby' => 'meta_value_num title',
			'meta_key' => '_montera34_project_card_date_ini'
		);
		$projects = get_posts($args);
		$collabora_projects_out = "<section id='collabora-projects'><h3 class='tit-upper'>" .sprintf( __('Projects in which %s has collaborated','montera34'), $tit ). "</h3><div class='list'>";
			foreach ( $projects as $project ) {
				$project_perma = get_permalink($project->ID);
				$project_tit = $project->post_title;
				$project_desc = $project->post_excerpt;
				$project_year_ini = get_post_meta( $project->ID, '_montera34_project_card_date_ini', true );
				if ( $project_year_ini != '' ) { $project_year = "<span class='list-item-year'>" .$project_year_ini. "</span>"; }
				else { $project_year = ""; }
				if ( has_post_thumbnail($project->ID) ) {
					$project_img = "<figure class='list-item-img'><a href=" .$project_perma. ">" .get_the_post_thumbnail($project->ID,'thumbnail',array('class' => 'img-responsive')). "</a></figure>";
				} else { $collabora_img = ""; }
				$project_roles = get_post_meta( $post->ID, '_montera34_collabora_projects', true );
				foreach ( $project_roles as $rol ) {
					if ( $rol['project'] == $project->ID ) { $project_rol = $rol['rol']; }
				}
				$collabora_projects_out .=
				"<div class='list-item list-project'>
					<header><h4 class='list-item-tit'><a href='" .$project_perma. "' title='" .$project_tit. "'>" .$project_tit. "</a></h4>
					<div class='list-item-desc'>
						<div class='list-item-rol'>" .__('Rol','montera34'). ": " .$project_rol. "</div>
						<p>" .$project_desc . $project_year. "</p>
						" .$project_img. "
					</div>
				</div>
				";
			} // end foreach collaborators
		$collabora_projects_out .= "</div></section>";
	}

// if it is an attachement
} elseif  (is_attachment()) {
	$caption_attachment = get_the_excerpt();
	$alt_attachment = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
	$subtit = "
	<div class='subtit'>
		<a href='".get_permalink($post->post_parent). "' rev='attachment' title='".__('Back to project','montera34'). " " .get_the_title($post->post_parent)."'>
			&laquo; ". __("Back to project","montera34"). " " .get_the_title($post->post_parent)."
		</a>
	</div>
	";
	$collabora_img_out = "";
	$collaboras_out = "";
	$collabora_projects_out = "";
	$related_out = "";
	$subtit_link = "";

	$imageurl = wp_get_attachment_image_src( $post->ID, 'large');
	$imageurlfull = wp_get_attachment_image_src( $post->ID, 'full');
	
	$content =
	"<div class='row'>
		<figure id='attachment' class='col-md-12 wp-caption' aria-describedby='figcaption_attachment' itemscope='' itemtype='http://schema.org/ImageObject'>
			<a href='" .$imageurlfull[0]. "'><img src='" .$imageurl[0]. "' class='img-responsive' alt='" .$alt_attachment. "' ></a>
			<figcaption id='figcaption_attachment' class='wp-caption-text' itemprop='description'>" .$caption_attachment. "</figcaption>
		</figure>
	</div>
	";
} // end vars depending on post type
?>

		<header class="main-tit">
			<h1><?php echo $tit; ?></h1>
			<?php echo $subtit ?>
			<?php echo $subtit_url; ?>
		</header>
		
		<article class="row single-content">
			<div class="<?php echo (is_attachment()==false) ? 'col-md-9' : 'col-md-12'; ?>">
				<section>
				<?php if ( is_attachment() ) { ?>
					<ul class="pager">
						<li class="previous"><?php previous_image_link( false, '&laquo; Previous image' ); ?></li>
						<li class="next"><?php next_image_link( false, 'Next image &raquo;' ); ?></li>
					</ul>
				<?php }
				 echo $content; ?>
				</section>
				<?php echo $collabora_projects_out; ?>
			</div>
			<?php if (is_attachment()==false) { //if it is not an attachment ?>
			<div class="col-md-3">
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
			</div><!-- .col-md-3 -->
			<?php	} ?>
		</article><!-- .row -->

	<?php endwhile;
} // end if posts
?>


<?php get_footer(); ?>
