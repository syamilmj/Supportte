<?php

/**
 * Single User Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">

	<?php do_action( 'bbp_template_notices' ); ?>

	<?php bbp_get_template_part( 'user', 'details' ); ?>

<?php if(is_user_logged_in()) : ?>

	<?php bbp_get_template_part( 'user', 'subscriptions' ); ?>

	<?php bbp_get_template_part( 'user', 'favorites' ); ?>

	<?php bbp_get_template_part( 'user', 'topics-created' ); ?>

<?php else : ?>

	<div class="bbp-template-notice">
		<p>Sorry, but you do not have permission to view complete user profile</p>
	</div>

<?php endif; ?>

</div>
