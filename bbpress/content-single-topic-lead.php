<?php

/**
 * Single Topic Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_lead_topic' ); ?>

<ul id="bbp-topic-<?php bbp_topic_id(); ?>-lead" class="bbp-lead-topic">

	<li class="bbp-header">

		<?php bbp_get_template_part('meta', 'single-topic'); ?>

	</li><!-- .bbp-header -->

	<li class="bbp-body">

		<div id="post-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

			<div class="bbp-topic-author">

				<?php do_action( 'bbp_theme_before_topic_author_details' ); ?>

				<?php bbp_topic_author_link( array( 'size' => 60, 'sep' => '<br />', 'show_role' => true, 'type' => 'avatar' ) ); ?>

				<?php if ( is_super_admin() ) : ?>

					<?php do_action( 'bbp_theme_before_topic_author_admin_details' ); ?>

					<div class="bbp-topic-ip"><?php bbp_author_ip( bbp_get_topic_id() ); ?></div>

					<?php do_action( 'bbp_theme_after_topic_author_admin_details' ); ?>

				<?php endif; ?>

				<?php do_action( 'bbp_theme_after_topic_author_details' ); ?>

			</div><!-- .bbp-topic-author -->

			<div class="bbp-topic-content">

				<?php do_action( 'bbp_theme_before_topic_content' ); ?>
				
				<div class="topic-author-displayname"><?php bbp_topic_author() ?></div>
				
				<div class="bbp-meta">
				
					<?php bbp_topic_post_date(); ?>
					
					<a href="<?php bbp_topic_permalink(); ?>" title="<?php bbp_topic_title(); ?>" class="bbp-topic-permalink">#<?php bbp_topic_id(); ?></a>
		
				</div><!-- .bbp-meta -->

				<?php bbp_topic_content(); ?>
				
				<?php do_action( 'bbp_theme_after_topic_content' ); ?>

			</div><!-- .bbp-topic-content -->
			
			<?php bbp_get_template_part('meta', 'private'); ?>

		</div><!-- #post-<?php bbp_topic_id(); ?> -->

	</li><!-- .bbp-body -->

	<li class="bbp-footer">

	</li>

</ul><!-- #topic-<?php bbp_topic_id(); ?>-replies -->

<?php do_action( 'bbp_template_after_lead_topic' ); ?>
