<?php  /* Template Name: Page Full Width */
get_header(); ?>

<?php if ( have_posts() ) {
	while ( have_posts() ) : the_post(); ?>

		<section>
		<header class="main-tit"><h1><?php the_title() ?></h1></header>
		<div class="row">
			<div class="col-md-12">
				<?php the_content(); ?>
			</div><!-- .col-md-12 -->
		</div><!-- .row -->
		</section>

	<?php endwhile;
} ?>
<?php get_footer(); ?>
