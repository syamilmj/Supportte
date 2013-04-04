<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

/**
 * Display forum search results
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
			
			<?php if($search) { ?>
			
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
			
			<?php } else { ?>

			<?php if ( have_posts() ) : ?>
							<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyten' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
							<?php
							/* Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called loop-search.php and that will be used instead.
							 */
							 get_template_part( 'loop', 'search' );
							?>
			<?php else : ?>
							<div id="post-0" class="post no-results not-found">
								<h2 class="entry-title"><?php _e( 'Nothing Found', 'twentyten' ); ?></h2>
								<div class="entry-content">
									<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyten' ); ?></p>
									<?php get_search_form(); ?>
								</div><!-- .entry-content -->
							</div><!-- #post-0 -->
			<?php endif; ?>
			
			<?php } ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
