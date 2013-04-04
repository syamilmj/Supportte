<?php
/*
Template Name: Portfolio template
*/

get_header(); ?>

		<div id="container">
			<div id="content" role="main">
				<div class="breadcrumbs">
				<?php if( isset( $wp_query->query_vars["technologies"] ) ) {
					$term = get_term_by('slug', $wp_query->query_vars["technologies"], 'portfolio_technologies');
					echo __('Technologies', 'portfolio').": ".( $term->name );
				}
				else {
					the_title(); 
				} ?>
				</div>
			
				<?php global $wp_query;
				$portfolio_options = get_option( 'prtfl_options' );
				$paged = isset( $wp_query->query_vars['paged'] ) ? $wp_query->query_vars['paged'] : 1;
				$technologies = isset( $wp_query->query_vars["technologies"] ) ? $wp_query->query_vars["technologies"] : "";
				if( $technologies != "" ) {
					$args = array(
						'post_type'					=> 'portfolio',
						'post_status'				=> 'publish',
						'orderby'						=> $portfolio_options['prtfl_order_by'],
						'order'							=> $portfolio_options['prtfl_order'],
						'posts_per_page'		=> get_option( 'posts_per_page' ),
						'paged'							=> $paged,
						'tax_query' => array(
								array(
									'taxonomy' => 'portfolio_technologies',
									'field' => 'slug',
									'terms' => $technologies
								)
							)
						);
				}
				else {
					$args = array(
						'post_type'					=> 'portfolio',
						'post_status'				=> 'publish',
						'orderby'						=> $portfolio_options['prtfl_order_by'],
						'order'							=> $portfolio_options['prtfl_order'],
						'posts_per_page'		=> get_option( 'posts_per_page' ),
						'paged'							=> $paged
						);
				}

				query_posts( $args );
				
				while ( have_posts() ) : the_post(); ?>
					<div class="portfolio_content">
						<div class="entry">
							<?php global $post;
							$meta_values				= get_post_custom($post->ID);
							$post_thumbnail_id	= get_post_thumbnail_id( $post->ID );
							if( empty ( $post_thumbnail_id ) ) {
								$args = array(
									'post_parent' => $post->ID,
									'post_type' => 'attachment',
									'post_mime_type' => 'image',
									'numberposts' => 1
								);
								$attachments				= get_children( $args );
								$post_thumbnail_id	= key($attachments);
							}
							$image						= wp_get_attachment_image_src( $post_thumbnail_id, 'portfolio-thumb' );
							$image_large			= wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
							$image_alt				= get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
							$image_desc 			= get_post($post_thumbnail_id);
							$image_desc				= $image_desc->post_content;
							if( get_option( 'prtfl_postmeta_update' ) == '1' ) {
								$post_meta		= get_post_meta( $post->ID, 'prtfl_information', true);
								$date_compl		= $post_meta['_prtfl_date_compl'];
								if( ! empty( $date_compl ) && 'in progress' != $date_compl) {
									$date_compl		= explode( "/", $date_compl );
									$date_compl		= date( get_option( 'date_format' ), strtotime( $date_compl[1]."-".$date_compl[0].'-'.$date_compl[2] ) );
								}
								$link					= $post_meta['_prtfl_link'];
								$short_descr	= $post_meta['_prtfl_short_descr'];
							}
							else{
								$date_compl		= get_post_meta( $post->ID, '_prtfl_date_compl', true );
								if( ! empty( $date_compl ) && 'in progress' != $date_compl) {
									$date_compl		= explode( "/", $date_compl );
									$date_compl		= date( get_option( 'date_format' ), strtotime( $date_compl[1]."-".$date_compl[0].'-'.$date_compl[2] ) );
								}
								$link					= get_post_meta($post->ID, '_prtfl_link', true);
								$short_descr	= get_post_meta($post->ID, '_prtfl_short_descr', true); 
							} ?>

							<div class="portfolio_thumb">
								<a class="lightbox" rel="portfolio_fancybox" href="<?php echo $image_large[0]; ?>" title="<?php echo $image_desc; ?>">
									<img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" alt="<?php echo $image_alt; ?>" />
								</a>
							</div>
							<div class="portfolio_short_content">
								<div class="item_title">
									<p>
										<a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php echo get_the_title(); ?></a>
									</p>
								</div> <!-- .item_title -->
								<?php if( 1 == $portfolio_options['prtfl_date_additional_field'] ) { ?>
										<p>
											<span class="lable"><?php echo $portfolio_options['prtfl_date_text_field']; ?></span> <?php echo $date_compl; ?>
										</p>
								<?php } 
								$user_id = get_current_user_id();
								if( 1 == $portfolio_options['prtfl_link_additional_field'] ) { ?>
										<?php if ( $user_id == 0 ) { ?>
												<p><span class="lable"><?php echo $portfolio_options['prtfl_link_text_field']; ?></span> <?php echo $link; ?></p>
										<?php }
										else if( parse_url( $link ) !== false ) { ?>
												<p><span class="lable"><?php echo $portfolio_options['prtfl_link_text_field']; ?></span> <a href="<?php echo $link; ?>"><?php echo $link; ?></a></p>
										<?php } else { ?>
												<p><span class="lable"><?php echo $portfolio_options['prtfl_link_text_field']; ?></span> <?php echo $link; ?></p>
										<?php } ?>
								<?php }
								if( 1 == $portfolio_options['prtfl_shrdescription_additional_field'] ) { ?>
										<p><span class="lable"><?php echo $portfolio_options['prtfl_shrdescription_text_field']; ?></span> <?php echo $short_descr; ?></p>
								<?php } ?>
							</div> <!-- .portfolio_short_content -->
						</div> <!-- .entry -->
						<div class="entry_footer">
							<div class="read_more">
								<a href="<?php the_permalink(); ?>" rel="bookmark"><?php _e( 'Read more', 'portfolio' ); ?></a>
							</div> <!-- .read_more -->
							<?php $terms = wp_get_object_terms( $post->ID, 'portfolio_technologies' ) ;
							if ( is_array( $terms ) && count( $terms ) > 0) { ?>
								<div class="portfolio_terms"><?php echo $portfolio_options['prtfl_technologies_text_field']; ?>
								<?php $count = 0;
								foreach ( $terms as $term ) {
									if( $count > 0 ) 
										echo ', '; 
									echo '<a href="'. get_term_link( $term->slug, 'portfolio_technologies') . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a>';
									$count++;
								} ?>
								</div>
							<?php } ?>
						</div> <!-- .entry_footer -->
					</div> <!-- .portfolio_content -->
			<?php endwhile; 
			$portfolio_options = get_option( 'prtfl_options' ); ?>
			
				<script type="text/javascript">
				(function($){
					$(document).ready(function(){
						$("a[rel=portfolio_fancybox]").fancybox({
							'transitionIn'		: 'elastic',
							'transitionOut'		: 'elastic',
							'titlePosition' 	: 'inside',
							'speedIn'					:	500, 
							'speedOut'				:	300,
							'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
								return '<span id="fancybox-title-inside">' + (title.length ? title + '<br />' : '') + 'Image ' + (currentIndex + 1) + ' / ' + currentArray.length + '</span>';
							}
						});
					});
				})(jQuery);
				</script>
			</div><!-- #content -->
			<div id="portfolio_pagenation">
			<?php if( function_exists( 'prtfl_pagination' ) ) prtfl_pagination(); ?>
			<input type="hidden" value="Version=2.08" />
			</div>
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>