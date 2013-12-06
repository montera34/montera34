<?php
// title
if ( is_home() ) {
	$terms = get_the_terms($post_id,'montera34_type');
	foreach ( $terms as $term ) {
		$loop_tit = $term->name;
		$term_perma = get_term_link($term);
	}
	$loop_desc = get_the_title();
} else {
	$loop_tit = get_the_title();
	$loop_desc = get_the_excerpt();
}

// permalink
$project_perma = get_permalink();

// featured image
if ( has_post_thumbnail() ) {
	$art_featured = get_the_post_thumbnail($post_id,array(500,100)); // falta configurar $size correctamente
	$loop_featured = "<figure>" .$art_featured. "</figure>";
} else { $loop_featured = ""; }

?>

<article>
	<header><h2><?php echo $loop_tit ?></h2></header>
		<?php echo $term_perma // pensar donde meter el enlace?>
	<?php echo $loop_featured ?>
	<div><?php echo $loop_desc ?></div>
		<?php echo $project_perma // pensar donde meter el enlace?>
</article>
