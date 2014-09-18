<?php
// collaborators list loop

$loop_perma = get_permalink();
$loop_tit = get_the_title();
$loop_desc = get_the_excerpt();
// featured image
if ( has_post_thumbnail() ) {
	$loop_featured = get_the_post_thumbnail($post->ID,'bigicon',array('class' => 'img-responsive'));
} else { $loop_featured = ""; }

// collaborator's projects
$collabora_projects = get_post_meta( $post->ID, '_montera34_collabora_projects', true );
$projects_count = count($collabora_projects);
if ( $projects_count >= 1 ) {
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
	unset($project_ids);
	$collabora_projects_out = sprintf( _n('<strong>1 project</strong> with us','<strong>%s projects</strong> with us',$projects_count,'montera34' ),$projects_count). ": ";
			foreach ( $projects as $project ) {
				$project_perma = get_permalink($project->ID);
				$project_tit = $project->post_title;
				//$project_roles = get_post_meta( $post->ID, '_montera34_collabora_projects', true );
				$project_rol_out = "";
				foreach ( $collabora_projects as $rol ) {
					if ( $rol['project'] == $project->ID && $rol['rol'] != '' ) {
						$project_rol_out .= " <em>(" .$rol['rol']. ")</em>";
					}
				}
				$collabora_projects_out .= "<span class='media-footer-item'><a href='" .$project_perma. "' title='" .$project_tit. "'>" .$project_tit. "</a>" .$project_rol_out. "</span>, ";
			} // end foreach collaborator's projects
			$collabora_projects_out = substr($collabora_projects_out, 0, -2);
} else { $collabora_projects_out = ""; } // end if collaborator has projects
?>

<article class="media-collabora media">
	<a class="pull-left" href="<?php echo $loop_perma ?>"><?php echo $loop_featured ?></a>
	<div class="media-body">
		<header><h2 class="media-heading"><a href="<?php echo $loop_perma ?>"><?php echo $loop_tit ?></a></h2></header>
		<div class="media-desc"><?php echo $loop_desc; ?></div>
		<footer class="media-footer"><?php echo $collabora_projects_out; ?></footer>
	</div>
</article>

