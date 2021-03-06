<?php
// projects list loop

// common vars
$project_perma = get_permalink();
if ( has_post_thumbnail() ) {
	$loop_featured = "<figure class='list-item-img'><a href=" .$project_perma. ">" .get_the_post_thumbnail($post->ID,'thumbnail',array('class' => 'img-responsive')). "</a></figure>";
} else { $loop_featured = ""; }
$loop_desc = get_the_excerpt();
$loop_year_ini = get_post_meta( $post->ID, '_montera34_project_card_date_ini', true );
if ( $loop_year_ini != '' && !is_home() ) { $loop_year = "<span class='list-item-year'>" .$loop_year_ini. "</span>"; }
else { $loop_year = ""; }

// vars depending on the view
if ( is_home() ) {
	$loop_tag = "section";
	$loop_class = "list-home";
	$terms = get_the_terms($post->ID,'montera34_type');
	foreach ( $terms as $term ) {
		$loop_tit = $term->name;
		$loop_perma = get_term_link($term);
	}
	$loop_subtit = "<strong>" .get_the_title(). "</strong>. ";
	$loop_terms = "";

} elseif ( is_tax() ) {
	$loop_tag = "article";
	$loop_class = "list-tax";
	$loop_tit = get_the_title();
	$loop_perma = $project_perma;
	$loop_subtit = "";
	$terms = get_the_terms($post->ID,'montera34_tech');
	if ( $terms == false ) { $loop_terms = "";  } else {
		$loop_terms = "<ul class='list-inline'>";
		foreach ( $terms as $term ) {
			$term_tit = $term->name;
			$term_perma = get_term_link($term);
			$loop_terms .= "<li><a href='" .$term_perma. "'>" .$term_tit. "</a></li>";
		}
		$loop_terms .= "</ul>";
	}

} else {
	$loop_tag = "article";
	$loop_class = "list-project";
	$loop_tit = get_the_title();
	$loop_perma = $project_perma;
	$loop_subtit = "";
	$terms = get_the_terms($post->ID,'montera34_type');
	if ( $terms == false ) { $loop_terms = "";  } else {
		$loop_terms = "<ul class='list-item-context list-inline'>";
		foreach ( $terms as $term ) {
			$term_tit = $term->name;
			$term_perma = get_term_link($term);
			$loop_terms .= "<li><a href='" .$term_perma. "'>" .$term_tit. "</a></li>";
		}
		$loop_terms .= "</ul>";
	}

}

?>

<<?php echo $loop_tag ?> class="list-item <?php echo $loop_class ?>">
	<header>
		<h2 class="list-item-tit"><a href="<?php echo $loop_perma ?>"><?php echo $loop_tit ?></a></h2>
		<?php if ( is_user_logged_in() ) { ?><div class="btn btn-xs btn-default pull-right"> <?php edit_post_link(__('Edit This','montera34')); ?></div> <?php } ?>
	</header>
	<div class="list-item-text">
		<div class="list-item-desc"><?php echo $loop_subtit . $loop_desc. " " .$loop_year; ?></div>
		<?php echo $loop_terms ?>
	</div>
	<?php echo $loop_featured ?>
	
</<?php echo $loop_tag ?>>

