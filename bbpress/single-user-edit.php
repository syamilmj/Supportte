<?php

get_header(); ?>
	
	<div id="container">
		<div id="content" role="main">

			<?php do_action( 'bbp_before_main_content' ); ?>

			<div id="bbp-user-<?php bbp_current_user_id(); ?>" class="bbp-single-user">
				<div class="entry-content">

					<?php bbp_get_template_part( 'content', 'single-user-edit' ); ?>

				</div><!-- .entry-content -->
			</div><!-- #bbp-user-<?php bbp_current_user_id(); ?> -->

			<?php do_action( 'bbp_after_main_content' ); ?>

		</div>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>