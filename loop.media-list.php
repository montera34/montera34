<?php
// collaborators list loop

$loop_perma = get_permalink();
$loop_tit = get_the_title();
$loop_desc = get_the_excerpt();
// featured image
if ( has_post_thumbnail() ) {
	$loop_featured = get_the_post_thumbnail($post->ID,'bigicon',array('class' => 'img-responsive'));
} else { $loop_featured = ""; }


?>

<article class="media">
	<a class="pull-left" href="<?php echo $loop_perma ?>"><?php echo $loop_featured ?></a>
	<div class="media-body">
		<header><h2 class="media-heading"><a href="<?php echo $loop_perma ?>"><?php echo $loop_tit ?></a></h2></header>
		<p><?php echo $loop_desc; ?></p>
	</div>
</article>

