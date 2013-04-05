<?php

/**
 * User Details
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

	<?php do_action( 'bbp_template_before_user_details' ); ?>

	<span class="page-title author">

		<?php printf( __( 'Profile: %s', 'bbpress' ), "<span class='vcard'><a class='url fn n' href='" . bbp_get_user_profile_url() . "' title='" . esc_attr( bbp_get_displayed_user_field( 'display_name' ) ) . "' rel='me'>" . bbp_get_displayed_user_field( 'display_name' ) . "</a></span>" ); ?>

		<?php if ( bbp_is_user_home() || current_user_can( 'edit_users' ) ) : ?>

			<span class="edit_user_link"><a href="<?php bbp_user_profile_edit_url(); ?>" title="<?php printf( __( 'Edit Profile of User %s', 'bbpress' ), esc_attr( bbp_get_displayed_user_field( 'display_name' ) ) ); ?>"><?php _e( '(Edit)', 'bbpress' ); ?></a></span>

		<?php endif; ?>

	</span>

	<div id="entry-author-info">
		<div id="author-avatar">

			<?php echo get_avatar( bbp_get_displayed_user_field( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>

		</div><!-- #author-avatar -->
		<div id="author-description">
			<h1><?php printf( __( 'About %s', 'bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ); ?></h1>

			<?php bbp_displayed_user_field( 'description' ); ?>

		</div><!-- #author-description	-->
	</div><!-- #entry-author-info -->

	<?php 

	$user_id = bbp_get_user_id();
	$curr_user_id = bbp_get_user_id('', false, true);
	$items = get_user_meta($user_id, $key = 'purchased_items', $single = true);

	if( current_user_can('manage_options') || $user_id == $curr_user_id && is_user_logged_in() ) : ?>
	
	<?php if($items) { ?>
	<div id="purchase-details">

		<h2 class="entry-title">Purchase Details</h2>
		<div class="bbp-user-section">

			<?php foreach($items as $item) { ?>
			<ul>
				<li><strong>Item: </strong><?php echo $item['name']?></li>
				<li><strong>Buyer: </strong><?php echo $item['buyer']?></li>
				<li><strong>Date: </strong><?php echo date(get_option('date_format').' '.get_option('time_format'), strtotime($item['date']))?></li>
				<?php if( current_user_can('manage_options') ) { ?><li><strong>Purchase Code: </strong><?php echo $item['purchase_code']?></li><?php } ?>
			</ul>
			<?php } ?>

		</div>

	</div>
	<?php } ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_user_details' ); ?>
