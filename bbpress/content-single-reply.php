<?php

/**
 * Single Reply Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">

	<ul>
	
		<li class="bbp-header"></li>
		
		<?php do_action( 'bbp_template_before_single_reply' ); ?>
		
		<li class="bbp-body">
		
			<?php if ( post_password_required() ) : ?>
		
				<?php bbp_get_template_part( 'form', 'protected' ); ?>
		
			<?php else : ?>
		
				<?php 
				if(is_user_logged_in()) :
				
					bbp_get_template_part( 'loop', 'single-reply' ); 
				
				else :
					
					echo '<br/>';
					bbp_get_template_part('meta', 'private');
					echo '<br/>';
				
				endif;
				
				?>
		
			<?php endif; ?>
			
		</li>
	
		<?php do_action( 'bbp_template_after_single_reply' ); ?>
		
		<li class="bbp-footer"></li>
	
	</ul>

</div>
