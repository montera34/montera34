<?php  /* Template Name: Page blog with feeds */
get_header(); ?>

<?php if ( have_posts() ) {
	while ( have_posts() ) : the_post(); ?>

		<section>
		<header class="main-tit"><h1><?php the_title() ?></h1></header>
		<div class="row">
			<div class="col-md-9">
				<?php the_content(); ?>

				<h2><a href="http://voragine.net/">voragine.net</a></h2>
				<?php // Get RSS Feed(s). Code retrieved from http://codex.wordpress.org/Function_Reference/fetch_feed
				include_once( ABSPATH . WPINC . '/feed.php' );

				// Get a SimplePie feed object from the specified feed source
				$rss = fetch_feed( 'http://voragine.net/feed/' );
				$maxitems = 0;
				if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly
					// Figure out how many total items there are, but limit it to 5.
					$maxitems = $rss->get_item_quantity( 5 );
					// Build an array of all the items, starting with element 0 (first element).
					$rss_items = $rss->get_items( 0, $maxitems );
				endif;
				?>
				<ul>
					<?php if ( $maxitems == 0 ) : ?>
						<li><?php _e( 'No items', 'my-text-domain' ); ?></li>
					<?php else : ?>
						<?php // Loop through each feed item and display each item as a hyperlink. ?>
						<?php foreach ( $rss_items as $item ) : ?>
						<li>
							<a href="<?php echo esc_url( $item->get_permalink() ); ?>"
							title="<?php printf( __( 'Posted %s', 'voragine.net' ), $item->get_date('j F Y | g:i a') ); ?>">
							<?php echo esc_html( $item->get_title() ); ?>
							</a>
						</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>

				<h2><a href="http://numeroteca.org/">numeroteca.org</a></h2>
				<?php
				$rss = fetch_feed( 'http://numeroteca.org/tag/montera34blog/feed/' );
				$maxitems = 0;
				if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly
					$maxitems = $rss->get_item_quantity( 5 );
					$rss_items = $rss->get_items( 0, $maxitems );
				endif;
				?>
				<ul>
					<?php if ( $maxitems == 0 ) : ?>
						<li><?php _e( 'No items', 'my-text-domain' ); ?></li>
					<?php else : ?>
						<?php // Loop through each feed item and display each item as a hyperlink. ?>
						<?php foreach ( $rss_items as $item ) : ?>

						<li>
							<a href="<?php echo esc_url( $item->get_permalink() ); ?>"
							title="<?php printf( __( 'Posted %s', 'numeroteca.org' ), $item->get_date('j F Y | g:i a') ); ?>">
							<?php echo esc_html( $item->get_title() ); ?>
							</a>
						</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div><!-- .col-md-9 -->
		</div><!-- .row -->
		</section>

	<?php endwhile;
} ?>
<?php get_footer(); ?>
