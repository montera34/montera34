<?php
// collaborators list loop

// collaborator's projects
$collabora_projects = get_post_meta( $post->ID, '_montera34_collabora_projects', true );
if ( count($collabora_projects) >= 1 ) {
	foreach ( $collabora_projects as $project ) {
		$project_ids[] = $project['project'];
	}
	$args = array(
		'posts_per_page' => -1,
		'post__in' => $project_ids,
		'post_status' => array( 'publish' ),
		'post_type' => 'montera34_project'
	);
	$public_projects = get_posts($args);
	unset($project_ids);
	$public_projects_count = count($public_projects);
	if ( $public_projects_count >= 1 ) {

		$loop_perma = get_permalink();
		$loop_tit = get_the_title();
		$loop_desc = get_the_excerpt();
		// featured image
		if ( has_post_thumbnail() ) {
			$loop_featured = get_the_post_thumbnail($post->ID,'bigicon',array('class' => 'img-responsive'));
			$loop_featured_out = '<a class="pull-left" href="'.$loop_perma.'">'.$loop_featured.'</a>';
		} else {
			$loop_featured_out = '<div class="pull-left" style="width: 64px; margin: 0 10px 10px 0; text-align: center;"><i class="fa fa-user-secret fa-4x" aria-hidden="true"></i></div>';
		}

		$collabora_projects_out = sprintf( _n('<strong>1 project</strong> with us','<strong>%s projects</strong> with us',$public_projects_count,'montera34' ),$public_projects_count). ": ";
			foreach ( $public_projects as $project ) {
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
			$collabora_projects_out = substr($collabora_projects_out, 0, -2); ?>

<article class="media-collabora media">
	<?php echo $loop_featured_out ?>
	<div class="media-body">
		<header><h2 class="media-heading"><a href="<?php echo $loop_perma ?>"><?php echo $loop_tit ?></a></h2></header>
		<div class="media-desc"><?php echo $loop_desc; ?></div>
		<footer class="media-footer"><?php echo $collabora_projects_out; ?></footer>
	</div>
</article>

<?php } else { $collabora_projects_out = ""; } // end if collaborator has PUBLIC projects
} // end if collaborator has projects ?>
