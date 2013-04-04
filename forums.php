<?php
/** Template Name: Forums */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<?php 

				$exclude = array('page', '');

				// echo get_post_type();

				if( !in_array(get_post_type(), $exclude) ) { ?>

					<h1 class="entry-title"><?php the_title(); ?></h1>

				<?php } ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php
					
					$exclude = array('forum', 'topic', 'reply', '');

					if( !in_array(get_post_type(), $exclude) ) {
						bbp_get_template_part('meta', 'forum-intro');
					}

					?>

					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->

				</div><!-- #post-## -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>


