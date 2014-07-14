<?php
// title
if ( is_home() ) {
	$terms = get_the_terms($post->ID,'montera34_type');
	foreach ( $terms as $term ) {
		$loop_tit = $term->name. " [+]";
		$term_perma = get_term_link($term);
	}
	$loop_desc = get_the_title();
	$loop_excer = get_the_excerpt();
} else {
	$loop_tit = get_the_title();
	$loop_desc = get_the_excerpt();
}

// permalink
$project_perma = get_permalink();

// featured image
if ( has_post_thumbnail() ) {
	$art_featured = get_the_post_thumbnail($post->ID,'thumbnail',array('class' => 'img-responsive')); // falta configurar $size correctamente
	$loop_featured = "<figure>" .$art_featured. "</figure>";
} else { $loop_featured = ""; }

?>

<article>
<?php if ( is_home() ) {?>
	<header><h2><a href="<?php echo $term_perma ?>"><?php echo $loop_tit ?></a></h2></header>
	<strong><a href="<?php echo $project_perma ?>"><?php echo $loop_desc ?></a></strong><br>
	<?php echo $loop_excer ?>	
<?php } else { ?>
	<header><h2><a href="<?php echo $project_perma ?>"><?php echo $loop_tit ?></a></h2></header>
	<?php echo $loop_desc ?>
<?php } ?>
<a href="<?php echo $project_perma ?>"><?php echo $loop_featured ?></a>
</article>
