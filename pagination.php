<?php 
global $wp_rewrite;			
//$post_type = get_post_type();
$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
$total = $wp_query->max_num_pages;

// if more than 1 page, then build pagination
if ( $total > 1 ) {

	$pag_args = array(
		'total' => $total,
		'current' => $current,
		'mid_size' => 3,
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
		'type' => 'array',
	);
	
	if( $wp_rewrite->using_permalinks() ) { // if pretty permalinks
	
		if ( array_key_exists('s', $_GET) ) { // if search results
			$url_raw = "http://" .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$url_raw = preg_replace('/\/page\/[0-9]*/','',$url_raw);
			$pt_current = sanitize_text_field( $_GET['post_type'] );
	//	$pag_args['base'] = $url_clean. "page/%#%/", 'paged'). "?s=" .get_query_var('s'). "&post_type=" .$_GET['post_type'];
			$pag_args['base'] = user_trailingslashit( trailingslashit( remove_query_arg(array('s','post_type'),$url_raw)). "page/%#%/", 'paged'). "?s=" .get_query_var('s'). "&post_type=" .$pt_current;
		}
	
		else {
			$url_raw = "http://" .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$url_raw = preg_replace('/\/page\/[0-9]*/','',$url_raw);
			//$pag_args['base'] = user_trailingslashit( trailingslashit( remove_query_arg(get_pagenum_link(1))). "page/%#%/", 'paged');
			$pag_args['base'] = user_trailingslashit( trailingslashit( $url_raw ). "page/%#%/", 'paged' );
	
		}
	} // end if pretty permalink
	
	// query posts count
	//if ( is_home() ) { $counter = query_posts("showposts=-1"); $count_text = "art&iacute;culos"; }
	//elseif ( is_search() ) { $counter = query_posts("s=$s&showposts=-1"); $count_text = "resultados"; }
	//elseif ( $post_type == 'link' ) { $counter = query_posts("post_type=link&showposts=-1"); $count_text = "enlaces"; }
	//elseif ( is_category() ) { $query_cat = $wp_query->query_vars['cat']; $counter = query_posts("cat=$query_cat&showposts=-1"); $count_text = "art&iacute;culos"; }
	//	$count_posts = $wp_query->post_count;
	//	wp_reset_query();
	
	// output
	$pag_list = "";
	foreach ( paginate_links($pag_args) as $pag ) {
		if ( preg_match('/current/',$pag) == 1 ) { $pags_list .= "<li class='active'>" .$pag. "</li>"; }
		elseif ( preg_match('/dots/',$pag) == 1 ) { $pags_list .= "<li class='disabled'>" .$pag. "</li>"; }
		else { $pags_list .= "<li>" .$pag. "</li>"; }
	}
	
	$pag_out =
		"<ul class='pagination'>"
			.$pags_list.
		"</ul>";
	//echo "<div class='nav-counter'>$count_posts $count_text</div>";
	
	echo $pag_out;

} // end if more than 1 page
?>
