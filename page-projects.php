<?php /* Template Name: Page Projects List */ 
get_header(); ?>
		<header><h1><?php the_title(); ?></h1></header>
		<div class="row">
			<div class="col-md-12">
				<section>
					<table class="table">
						<thead>
							<tr>
								<th></th>
								<th><?php _e('Name','montera34'); ?></th>
								<th><?php _e('Code Repository','montera34'); ?></th>
								<th><?php _e('URL','montera34'); ?></th>
								<th><?php _e('Year','montera34'); ?></th>
								<th><?php _e('Type','montera34'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php //TODO move this to functions.php
										$args = array( 
											'post_type' => 'montera34_project',
											'posts_per_page' => -1,
											'post_parent' => 0,
											'order' => 'DESC',
											'orderby' => 'meta_value_num title',
											'meta_key' => '_montera34_project_card_date_ini',
									 		'tax_query' => array(
													array(
														'taxonomy' => 'montera34_type',
														'field'    => 'slug',
														'terms'    => 'desarrollo-web',//TODO localize
													),
												),
											'meta_query' => array(
												array(
														'key'     => '_montera34_project_card_code_repo',
														'compare'   => 'EXISTS', //TODO it must check if 'url' is filled!
													),
												),
										);
									$my_query = new WP_Query($args);
								if ( $my_query->have_posts() ) { while ( $my_query->have_posts() ) :  $my_query->the_post(); ?>
								<?php
									// common vars
									// permalink
									$project_perma = get_permalink();
									// featured image
									if ( has_post_thumbnail() ) {
										$loop_featured = "<figure><a href=" .$project_perma. ">" .get_the_post_thumbnail($post->ID,'icon',array('class' => 'img-responsive')). "</a></figure>";
									} else { $loop_featured = ""; }
								?>
							<tr <?php post_class(''); ?> id="post-<?php the_ID(); ?>">
								<td>
									<?php echo $loop_featured ?>
								</td>
								<td> <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to','montera34'); echo ' '.the_title_attribute(); ?>">
									<?php the_title(); ?></a>
									<?php if ( is_user_logged_in() ) { ?><div class="btn btn-xs btn-default pull-right"> <?php edit_post_link(__('Edit This','montera34')); ?></div> <?php } ?>
								</td>
								<td><?php
								$project_code_repo = get_post_meta( $post->ID, '_montera34_project_card_code_repo', true );
								if ( !empty($project_code_repo[0]) ) { echo "<a href='" .$project_code_repo[0]['url']. "'>" .$project_code_repo[0]['url_text']. "</a>"; }
								?> </td>
								<td>
									<?php $text = get_post_meta( $post->ID, '_montera34_project_card_project_url', true );
										echo "<a href='".$text."'>".$text."</a>"; ?>
								</td>
								<td><?php echo get_post_meta( $post->ID, '_montera34_project_card_date_ini', true ); ?>
								</td>
								<td><?php echo get_the_term_list( $post->ID, 'montera34_type', ' ', ', ', '' ); ?>
								</td>
							</tr>

								<?php endwhile;
								/* Restore original Post Data 
								 * NB: Because we are using new WP_Query we aren't stomping on the 
								 * original $wp_query and it does not need to be reset.
								*/
								wp_reset_postdata();

							} else {
								echo "<p>" .__('No projects.','montera34'). "</p>";
							} ?>
						</tbody>
					</table>
				</section>
			</div><!-- .col-md-12 -->
		</div><!-- .row -->

<?php get_footer(); ?>
