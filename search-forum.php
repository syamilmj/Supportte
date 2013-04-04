<?php
/** 
 * Template Name: Search Forum 
 *
 * Template for displaying forum search results
 */
get_header();

$http_get = ('GET' == $_SERVER['REQUEST_METHOD']); 
$search = $http_get ? $_GET['q'] : '';
$args = array(
	's' => $search,
);
?>

	<div id="container">
		<div id="content" role="main">

			<div id="forums-search">
				<form role="search" method="get" id="searchform" class="searchform" action="<?php echo site_url('/search') ?>">
					<input type="text" value="<?php echo $search ?>" name="q" class="search" placeholder="Search the forums..">
					<input type="submit" class="searchsubmit" value="Search">
				</form>
			</div>

			<br/>
			<hr/>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry-content">

						<div id="bbpress-forums">

							<?php if ( bbp_has_topics( $args ) ) : ?>

								<?php bbp_get_template_part( 'loop',       'topics'    ); ?>

								<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

							<?php else : ?>

									Sorry, no results found for <strong><?php echo $search ?></strong>.

							<?php endif; ?>

						</div>
						
					</div><!-- .entry-content -->

				</div><!-- #post-## -->

			<?php endwhile; endif; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>


