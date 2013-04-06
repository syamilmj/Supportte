<?php
/** Single topic meta */
?>


<div class="bbp-single-topic-meta">
	
	<div class="back-to">
		<a href="<?php echo home_url() ?>">&larr; Back to discussions</a>
	</div>

	<?php aq_display_topic_status(); ?>

	<div class="posted-in">
		Posted in: <?php echo '<a href="' . bbp_get_forum_permalink() . '" class="parent-forum">' . bbp_get_forum_title() . '</a>'; ?> &nbsp;
	</div>
	
</div>
