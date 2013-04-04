<?php

/**
 * bbPress Forum Widget
 *
 * Adds a widget which displays the forum list
 *
 * @since bbPress (r2653)
 *
 * @uses WP_Widget
 */
add_action( 'bbp_widgets_init', array( 'AQ_Forums_Widget',  'register_widget' ), 10 );
class AQ_Forums_Widget extends WP_Widget {

	/**
	 * bbPress Forum Widget
	 *
	 * Registers the forum widget
	 *
	 * @since bbPress (r2653)
	 *
	 * @uses apply_filters() Calls 'bbp_forums_widget_options' with the
	 *                        widget options
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'aq_widget_display_forums',
			'description' => __( 'A list of forums with an option to set the parent.', 'bbpress' )
		);

		parent::__construct( false, __( '(aqua) Forums List', 'bbpress' ), $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since bbPress (r3389)
	 *
	 * @uses register_widget()
	 */
	public static function register_widget() {
		register_widget( 'AQ_Forums_Widget' );
	}

	/**
	 * Displays the output, the forum list
	 *
	 * @since bbPress (r2653)
	 *
	 * @param mixed $args Arguments
	 * @param array $instance Instance
	 * @uses apply_filters() Calls 'bbp_forum_widget_title' with the title
	 * @uses get_option() To get the forums per page option
	 * @uses current_user_can() To check if the current user can read
	 *                           private() To resety name
	 * @uses bbp_has_forums() The main forum loop
	 * @uses bbp_forums() To check whether there are more forums available
	 *                     in the loop
	 * @uses bbp_the_forum() Loads up the current forum in the loop
	 * @uses bbp_forum_permalink() To display the forum permalink
	 * @uses bbp_forum_title() To display the forum title
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$title        = apply_filters( 'bbp_forum_widget_title', $instance['title'] );
		$parent_forum = !empty( $instance['parent_forum'] ) ? $instance['parent_forum'] : '0';

		// Note: private and hidden forums will be excluded via the
		// bbp_pre_get_posts_exclude_forums filter and function.
		$widget_query = new WP_Query( array(
			'post_parent'    => $parent_forum,
			'post_type'      => bbp_get_forum_post_type(),
			'posts_per_page' => get_option( '_bbp_forums_per_page', 50 ),
			'orderby'        => 'menu_order',
			'order'          => 'ASC'
		) );

		if ( $widget_query->have_posts() ) :

			echo $before_widget;
			echo $before_title . $title . $after_title; 
			$current_forum_id = bbp_get_forum_id();
			?>

			<ul>

				<?php while ( $widget_query->have_posts() ) : $widget_query->the_post(); ?>

					<?php
						$current = $widget_query->post->ID == $current_forum_id ? 'current': '';
					?>

					<li>
						<a class="bbp-forum-title <?php echo $current ?>" href="<?php bbp_forum_permalink( $widget_query->post->ID ); ?>" title="<?php bbp_forum_title( $widget_query->post->ID ); ?>">
							<?php bbp_forum_title( $widget_query->post->ID ); ?>
						</a>
						<span class="topic-count"><?php bbp_forum_topic_count( $widget_query->post->ID ) ?></span>
					</li>

				<?php endwhile; ?>

			</ul>

			<?php echo $after_widget;

			// Reset the $post global
			wp_reset_postdata();

		endif;
	}

	/**
	 * Update the forum widget options
	 *
	 * @since bbPress (r2653)
	 *
	 * @param array $new_instance The new instance options
	 * @param array $old_instance The old instance options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = $old_instance;
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['parent_forum'] = $new_instance['parent_forum'];

		// Force to any
		if ( !empty( $instance['parent_forum'] ) && !is_numeric( $instance['parent_forum'] ) ) {
			$instance['parent_forum'] = 'any';
		}

		return $instance;
	}

	/**
	 * Output the forum widget options form
	 *
	 * @since bbPress (r2653)
	 *
	 * @param $instance Instance
	 * @uses BBP_Forums_Widget::get_field_id() To output the field id
	 * @uses BBP_Forums_Widget::get_field_name() To output the field name
	 */
	public function form( $instance ) {
		$title        = !empty( $instance['title']        ) ? esc_attr( $instance['title']        ) : '';
		$parent_forum = !empty( $instance['parent_forum'] ) ? esc_attr( $instance['parent_forum'] ) : '0'; ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bbpress' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'parent_forum' ); ?>"><?php _e( 'Parent Forum ID:', 'bbpress' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'parent_forum' ); ?>" name="<?php echo $this->get_field_name( 'parent_forum' ); ?>" type="text" value="<?php echo $parent_forum; ?>" />
			</label>

			<br />

			<small><?php _e( '"0" to show only root - "any" to show all', 'bbpress' ); ?></small>
		</p>

		<?php
	}
}