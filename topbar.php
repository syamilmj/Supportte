<?php
/** Top Bar */

?>

<div id="topbar">
	<div class="inner">
		
		<div class="left">
			Welcome to Aquagraphite Support Forum :)
		</div>

		<div class="right">
			<?php
			if(is_user_logged_in()) {
				echo '<a href="' . bbp_get_user_profile_edit_url( bbp_get_user_id('', false, true) ) .'">Edit Profile</a> or ';
				echo '<a href="' . wp_logout_url($redirect = home_url()) .'">Logout</a>';
			} else {
				echo '<a href="' .wp_login_url($redirect = home_url(), $force_reauth = false) .'">Login</a> or ';
				echo '<a href="' .wp_login_url() . '?action=register">Register</a>';
			}
			?>
		</div>

		<div class="clearfix"></div>

	</div>
</div>